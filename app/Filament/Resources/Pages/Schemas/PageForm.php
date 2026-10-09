<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state, string $operation): void {
                        if ($operation === 'create') {
                            $set('slug', Str::slug((string) $state));
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->regex('/^[a-z0-9-]+$/')
                    ->notIn(['up', 'admin', 'livewire', 'storage'])
                    ->unique(ignoreRecord: true)
                    ->helperText('The page address, e.g. "about" is shown at /about. Use "home" for the homepage.'),
                TextInput::make('meta_title')->label('SEO title'),
                Textarea::make('meta_description')->label('SEO description')->rows(3),
                Toggle::make('is_published')->label('Published')->default(true),
            ]);
    }
}
