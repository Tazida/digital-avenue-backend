<?php

namespace App\Filament\Resources\Inquiries;

use App\Filament\Resources\Inquiries\Pages\ManageInquiries;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Inbox for contact-form submissions. Inquiries come only from the website, so there is
 * no "create" button. Opening an inquiry marks it as read.
 */
class InquiryResource extends Resource
{
    public const STATUS_LABELS = [
        'new'     => 'New',
        'read'    => 'Read',
        'replied' => 'Replied',
    ];

    protected static ?string $model = Inquiry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static string|UnitEnum|null $navigationGroup = 'Inbox';

    protected static ?string $navigationLabel = 'Inquiries';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        $new = Inquiry::where('status', 'new')->count();

        return $new > 0 ? (string) $new : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    /** Read-only details, shown in the "View" modal. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name'),
                TextInput::make('company'),
                TextInput::make('email'),
                TextInput::make('phone'),
                Select::make('service_id')
                    ->label('Service of interest')
                    ->relationship('service', 'title'),
                Select::make('status')
                    ->options(self::STATUS_LABELS),
                Textarea::make('message')
                    ->rows(8)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->description(fn (Inquiry $record): string => $record->email)
                    ->weight(fn (Inquiry $record): ?string => $record->status === 'new' ? 'bold' : null),
                TextColumn::make('company')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('service.title')
                    ->label('Service')
                    ->placeholder('—')
                    ->wrap(),
                TextColumn::make('message')
                    ->limit(60)
                    ->wrap(),
                SelectColumn::make('status')
                    ->options(self::STATUS_LABELS)
                    ->selectablePlaceholder(false),
            ])
            ->filters([
                SelectFilter::make('status')->options(self::STATUS_LABELS),
            ])
            ->recordActions([
                ViewAction::make()
                    ->mutateRecordDataUsing(function (array $data, Inquiry $record): array {
                        if ($record->status === 'new') {
                            $record->update(['status' => 'read']);
                            $data['status'] = 'read';
                        }

                        return $data;
                    }),
                Action::make('reply')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->color('gray')
                    ->url(fn (Inquiry $record): string => 'mailto:'.$record->email.'?subject='.rawurlencode('Re: Your inquiry to Digital Avenue'))
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInquiries::route('/'),
        ];
    }
}
