<?php

namespace App\Filament\Resources\StaffRoles;

use App\Filament\Resources\StaffRoles\Pages\ManageStaffRoles;
use App\Models\StaffRole;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class StaffRoleResource extends Resource
{
    protected static ?string $model = StaffRole::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Capabilities';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Workforce';

    protected static ?string $slug = 'workforce';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->label('Role')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('count_own')
                    ->label('Own staff')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                TextInput::make('count_hire')
                    ->label('Hired')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->paginated(false)
            ->columns([
                TextColumn::make('title')
                    ->label('Role'),
                TextColumn::make('count_own')
                    ->label('Own staff')
                    ->summarize(Sum::make()->label('Total')),
                TextColumn::make('count_hire')
                    ->label('Hired')
                    ->summarize(Sum::make()->label('Total')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStaffRoles::route('/'),
        ];
    }
}
