<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Newest added on top, so a project you just created is easy to find.
            // Click "Years" to see the order used on the website.
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->wrap()
                    ->description(fn (Project $record): ?string => $record->location),
                TextColumn::make('client.name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('years')
                    ->label('Years')
                    ->state(fn (Project $record): ?string => $record->yearsLabel())
                    ->placeholder('No date')
                    ->color(fn (Project $record): ?string => $record->start_date || $record->end_date ? null : 'warning')
                    // Same order as the website: by the year the work ended (or started).
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderByRaw(
                        'COALESCE(end_date, start_date) '.($direction === 'asc' ? 'ASC' : 'DESC').' NULLS LAST'
                    )),
                TextColumn::make('images_count')
                    ->label('Photos')
                    ->counts('images')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),
                ToggleColumn::make('is_published')
                    ->label('Published'),
                ToggleColumn::make('is_featured')
                    ->label('Featured'),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('services')
                    ->relationship('services', 'title')
                    ->multiple()
                    ->preload(),
                TernaryFilter::make('is_published')->label('Published'),
                TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
