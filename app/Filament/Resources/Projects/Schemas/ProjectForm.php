<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Forms\SlugFields;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Project')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        SlugFields::title()
                            ->columnSpanFull(),
                        SlugFields::slug('/projects/{slug}')
                            ->columnSpanFull(),
                        Select::make('client_id')
                            ->label('Client')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('services')
                            ->relationship('services', 'title')
                            ->multiple()
                            ->preload()
                            ->helperText('Used by the service filter on the Projects page.'),
                        TextInput::make('location')
                            ->maxLength(255),
                        Select::make('status')
                            ->options(array_combine(Project::STATUSES, array_map('ucfirst', Project::STATUSES)))
                            ->default('completed')
                            ->required(),
                        DatePicker::make('start_date')
                            // The website lists projects newest first, so a published project needs a date.
                            ->required(fn (Get $get): bool => (bool) $get('is_published'))
                            ->helperText('Needed to publish: the website lists projects newest first.'),
                        DatePicker::make('end_date')
                            ->afterOrEqual('start_date'),
                        Textarea::make('summary')
                            ->rows(3)
                            ->helperText('One or two sentences for the project list.')
                            ->columnSpanFull(),
                        Textarea::make('scope')
                            ->rows(6)
                            ->helperText('Scope of work, one item per line.')
                            ->columnSpanFull(),
                    ]),

                Section::make('On the website')
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Shown on the public site.'),
                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->helperText('Shown on the Home page.'),
                    ]),

                // Contract amounts are private: only admins see or change them.
                Section::make('Contract value (private)')
                    ->columnSpan(1)
                    ->visible(fn (): bool => (bool) auth()->user()?->isAdmin())
                    ->schema([
                        TextInput::make('contract_value')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('BDT'),
                        Toggle::make('show_value')
                            ->label('Show on the public site')
                            ->helperText('Off by default. Turn on only with the client\'s approval.'),
                    ]),
            ]);
    }
}
