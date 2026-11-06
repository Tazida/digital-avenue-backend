<?php

namespace App\Filament\Resources\Offices;

use App\Filament\Resources\Offices\Pages\ManageOffices;
use App\Models\Office;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Company';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('label')
                    ->label('Office')
                    ->required()
                    ->maxLength(255)
                    ->helperText('e.g. Chattogram'),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(30),
                Textarea::make('address')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('map_url')
                    ->label('Map link')
                    ->url()
                    ->maxLength(1000)
                    ->helperText('Google Maps embed link (Share → Embed a map → copy the src address).')
                    ->columnSpanFull(),
                Toggle::make('is_head_office')
                    ->label('Head office'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('label')
                    ->label('Office'),
                TextColumn::make('address')
                    ->wrap(),
                TextColumn::make('phone')
                    ->placeholder('—'),
                IconColumn::make('is_head_office')
                    ->label('Head office')
                    ->boolean(),
                IconColumn::make('map_url')
                    ->label('Map')
                    ->boolean()
                    ->state(fn (Office $record): bool => filled($record->map_url)),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOffices::route('/'),
        ];
    }
}
