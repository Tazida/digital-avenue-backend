<?php

namespace App\Filament\Resources\Services;

use App\Filament\Forms\SlugFields;
use App\Filament\Resources\Services\Pages\ManageServices;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                SlugFields::title()->columnSpanFull(),
                SlugFields::slug('/services/{slug}')->columnSpanFull(),
                Select::make('category')
                    ->options(Service::CATEGORIES)
                    ->required(),
                TextInput::make('icon')
                    ->maxLength(100)
                    ->helperText('Optional icon name for the website.'),
                Textarea::make('summary')
                    ->rows(2)
                    ->helperText('One sentence for the services list.')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->rows(8)
                    ->helperText('Full text for the service page. Leave an empty line between paragraphs.')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Published')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->description(fn (Service $record): ?string => $record->summary ? null : 'No description yet'),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Service::CATEGORIES[$state] ?? $state),
                TextColumn::make('projects_count')
                    ->label('Projects')
                    ->counts('projects'),
                ToggleColumn::make('is_published')
                    ->label('Published'),
            ])
            ->filters([
                SelectFilter::make('category')->options(Service::CATEGORIES),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageServices::route('/'),
        ];
    }
}
