<?php

namespace App\Filament\Resources\CompanyInfos;

use App\Filament\Resources\CompanyInfos\Pages\EditCompanyInfo;
use App\Models\CompanyInfo;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * Site-wide company text. There is only one row, so the menu item opens its edit form directly.
 */
class CompanyInfoResource extends Resource
{
    protected static ?string $model = CompanyInfo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Company';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Company info';

    protected static ?string $slug = 'company-info';

    protected static ?string $modelLabel = 'company info';

    protected static ?string $pluralModelLabel = 'company info';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Basics')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tagline')
                            ->maxLength(255)
                            ->helperText('One-line pitch at the top of the Home page.'),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(30),
                        FileUpload::make('logo_url')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('company')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),
                Section::make('About page')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('intro')
                            ->label('Introduction')
                            ->rows(10)
                            ->helperText('Leave an empty line between paragraphs.'),
                        Textarea::make('vision')
                            ->rows(2),
                        Textarea::make('mission')
                            ->rows(4)
                            ->helperText('One point per line.'),
                        Textarea::make('founder_message')
                            ->label("Founder's message")
                            ->rows(8)
                            ->helperText('Leave an empty line between paragraphs.'),
                    ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => EditCompanyInfo::route('/'),
        ];
    }
}
