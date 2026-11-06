<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

/**
 * Title + slug pair used by every resource that has a public page (/projects/{slug}, ...).
 * The slug follows the title until someone edits the slug by hand.
 */
class SlugFields
{
    public static function title(string $name = 'title'): TextInput
    {
        return TextInput::make($name)
            ->required()
            ->maxLength(255)
            ->live(onBlur: true)
            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                if (($get('slug') ?? '') === Str::slug((string) $old)) {
                    $set('slug', Str::slug((string) $state));
                }
            });
    }

    public static function slug(string $path): TextInput
    {
        return TextInput::make('slug')
            ->required()
            ->maxLength(255)
            ->alphaDash()
            ->unique(ignoreRecord: true)
            ->helperText("Part of the page address: {$path}");
    }
}
