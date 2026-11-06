<?php

namespace App\Filament\Resources\Certifications;

use App\Filament\Resources\Certifications\Pages\ManageCertifications;
use App\Models\Certification;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CertificationResource extends Resource
{
    protected static ?string $model = Certification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static string|UnitEnum|null $navigationGroup = 'Company';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('issuer')
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('number')
                    ->label('Certificate number (private)')
                    ->maxLength(255)
                    ->helperText('Never shown on the public site.')
                    ->columnSpanFull(),
                DatePicker::make('issued_on'),
                DatePicker::make('expires_on')
                    ->afterOrEqual('issued_on'),
                // Certificate scans are private: stored outside the public folder.
                FileUpload::make('file_url')
                    ->label('Scan (private)')
                    ->disk('local')
                    ->directory('certifications')
                    ->visibility('private')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->maxSize(10240)
                    ->downloadable()
                    ->columnSpanFull(),
                Toggle::make('is_public')
                    ->label('Show on the website')
                    ->helperText('Shows the title, issuer and dates on the About page. The number and scan stay private.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->wrap()
                    ->description(fn (Certification $record): ?string => $record->issuer),
                TextColumn::make('expires_on')
                    ->label('Expires')
                    ->date()
                    ->placeholder('No expiry')
                    ->color(fn (Certification $record): ?string => match (true) {
                        $record->expires_on === null                => null,
                        $record->expires_on->isPast()               => 'danger',
                        $record->expires_on->lt(now()->addDays(60)) => 'warning',
                        default                                     => null,
                    })
                    ->description(fn (Certification $record): ?string => $record->expires_on?->isPast() ? 'Expired' : null),
                IconColumn::make('is_public')
                    ->label('On website')
                    ->boolean(),
            ])
            ->filters([
                Filter::make('expired')
                    ->query(fn (Builder $query): Builder => $query->whereDate('expires_on', '<', now())),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCertifications::route('/'),
        ];
    }
}
