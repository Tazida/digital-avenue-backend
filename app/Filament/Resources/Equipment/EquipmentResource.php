<?php

namespace App\Filament\Resources\Equipment;

use App\Filament\Resources\Equipment\Pages\ManageEquipment;
use App\Models\Equipment;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use UnitEnum;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'Capabilities';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = 'equipment';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('category_id')
                    ->label('List')
                    ->relationship('category', 'name')
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('capacity')
                    ->label('Size / capacity')
                    ->maxLength(255),
                Select::make('unit')
                    ->options(array_combine(Equipment::UNITS, Equipment::UNITS))
                    ->default('Nos')
                    ->required(),
                TextInput::make('qty_own')
                    ->label('Own')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                TextInput::make('qty_hire')
                    ->label('Hire')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                TextInput::make('condition')
                    ->default('In Order')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->defaultGroup(Group::make('category.name')->label('List')->titlePrefixedWithLabel(false))
            ->groupingSettingsHidden()
            ->paginated([25, 50, 100])
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('capacity')
                    ->placeholder('—'),
                TextColumn::make('qty_own')
                    ->label('Own')
                    ->formatStateUsing(fn (int $state, Equipment $record): string => $state ? "{$state} {$record->unit}" : '—'),
                TextColumn::make('qty_hire')
                    ->label('Hire')
                    ->formatStateUsing(fn (int $state, Equipment $record): string => $state ? "{$state} {$record->unit}" : '—'),
                TextColumn::make('condition')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('List')
                    ->relationship('category', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEquipment::route('/'),
        ];
    }
}
