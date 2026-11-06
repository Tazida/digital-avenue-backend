<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Resources\Clients\Pages\ManageClients;
use App\Models\Client;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ClientResource extends Resource
{
    public const TYPE_LABELS = [
        'un_agency'  => 'UN agency',
        'government' => 'Government',
        'private'    => 'Private',
    ];

    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('type')
                    ->options(self::TYPE_LABELS)
                    ->required(),
                TextInput::make('website')
                    ->url()
                    ->maxLength(255),
                FileUpload::make('logo_url')
                    ->label('Logo')
                    ->image()
                    ->disk('public')
                    ->directory('clients')
                    ->maxSize(2048)
                    ->columnSpanFull(),
                Toggle::make('show_logo')
                    ->label('Show logo on the website')
                    ->helperText('UN agency logos need the client\'s permission. Until then the name is shown as text.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('logo_url')
                    ->label('Logo')
                    ->disk('public')
                    ->imageHeight(32),
                TextColumn::make('name')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::TYPE_LABELS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'un_agency'  => 'info',
                        'government' => 'success',
                        default      => 'gray',
                    }),
                TextColumn::make('projects_count')
                    ->label('Projects')
                    ->counts('projects'),
                IconColumn::make('show_logo')
                    ->label('Logo shown')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')->options(self::TYPE_LABELS),
            ])
            ->recordActions([
                EditAction::make(),
                // Clients with projects cannot be deleted (the database blocks it).
                DeleteAction::make()
                    ->hidden(fn (Client $record): bool => ($record->projects_count ?? $record->projects()->count()) > 0),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageClients::route('/'),
        ];
    }
}
