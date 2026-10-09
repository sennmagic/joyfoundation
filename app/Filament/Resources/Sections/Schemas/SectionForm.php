<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sections\Schemas;

use App\Models\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SectionForm
{
    /** @param  list<string>  $types  Which section types the Type select offers. */
    public static function configure(Schema $schema, array $types = Section::TYPES): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('type')
                    ->options(collect($types)->mapWithKeys(function (string $type): array {
                        $label = e(Section::label($type));

                        if (! self::hasPreview($type)) {
                            return [$type => $label];
                        }

                        return [$type => '<img src="'.e(self::previewUrl($type)).'" alt="" style="display:inline-block;width:96px;height:48px;object-fit:cover;object-position:top;border-radius:6px;margin-right:12px;vertical-align:middle"><span style="vertical-align:middle">'.$label.'</span>'];
                    })->all())
                    ->allowHtml()
                    ->required()
                    ->live()
                    ->disabledOn('edit')
                    ->dehydrated(),
                Image::make(fn (Get $get): string => self::previewUrl((string) $get('type')), 'Section preview')
                    ->key('preview')
                    ->imageWidth('100%')
                    ->visible(fn (Get $get): bool => self::hasPreview((string) $get('type'))),
                Toggle::make('is_visible')->label('Show on homepage')->default(true),
                Group::make()
                    ->statePath('data')
                    ->schema(fn (Get $get): array => self::fieldsFor((string) $get('type'))),
            ]);
    }

    public static function previewUrl(string $type): string
    {
        return asset("images/admin/sections/{$type}.png");
    }

    public static function hasPreview(string $type): bool
    {
        return $type !== '' && file_exists(public_path("images/admin/sections/{$type}.png"));
    }

    /** @return array<int, Component> */
    private static function fieldsFor(string $type): array
    {
        return match ($type) {
            'seo' => [
                TextInput::make('title')->required(),
                Textarea::make('description')->rows(3),
            ],
            'nav' => [
                Repeater::make('items')->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('href')->required(),
                ])->columns(2),
            ],
            'social' => [
                Repeater::make('items')->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('url')->required(),
                    self::image('icon'),
                ])->columns(3),
            ],
            'hero' => [
                self::image('background'),
                ...self::heading(),
            ],
            'text_band' => [
                Textarea::make('text')->rows(3)->required(),
            ],
            'image_text' => [
                self::image('photo'),
                self::image('photo_polaroid'),
                TextInput::make('established_year')->required(),
                TextInput::make('eyebrow')->required(),
                TextInput::make('heading_prefix'),
                TextInput::make('heading_highlight'),
                self::lines('paragraphs'),
                TextInput::make('cta_label')->required(),
            ],
            'statements' => [
                TextInput::make('eyebrow')->required(),
                ...self::heading(),
                Textarea::make('intro')->rows(2),
                self::richBlock('mission'),
                self::richBlock('vision'),
                self::richBlock('guides'),
            ],
            'stats' => [
                TextInput::make('eyebrow')->required(),
                Fieldset::make('Heading')->statePath('heading')->schema([
                    TextInput::make('line1'),
                    TextInput::make('line2_prefix'),
                    TextInput::make('line2_highlight'),
                ]),
                self::lines('note'),
                Fieldset::make('Headline stat')->statePath('headline_stat')->schema([
                    TextInput::make('ghost')->helperText('Large faded text behind the number'),
                    TextInput::make('value')->required(),
                    self::lines('caption'),
                ]),
                Repeater::make('stats')->schema([
                    TextInput::make('value')->required(),
                    self::lines('caption'),
                    self::colour('color'),
                    self::weight(),
                ])->columns(2),
                Repeater::make('dark_stats')->label('Dark bar stats')->schema([
                    TextInput::make('value')->required(),
                    self::weight(),
                    TextInput::make('caption')->required(),
                    TextInput::make('sub_caption'),
                ])->columns(2),
            ],
            'card_grid' => [
                TextInput::make('eyebrow')->required(),
                ...self::heading(),
                Textarea::make('subtext')->rows(2),
                Repeater::make('items')->schema([
                    self::image('photo'),
                    self::image('icon'),
                    TextInput::make('category')->required(),
                    TextInput::make('title')->required(),
                    Textarea::make('description')->rows(3),
                ])->columns(2),
            ],
            'feature_list' => [
                TextInput::make('eyebrow')->required(),
                Fieldset::make('Heading')->statePath('heading')->schema([
                    TextInput::make('line1'),
                    TextInput::make('line2_prefix'),
                    TextInput::make('line2_highlight'),
                    TextInput::make('line2_suffix'),
                ]),
                Textarea::make('intro')->rows(3),
                Repeater::make('initiatives')->label('Items')->schema([
                    self::image('photo'),
                    TextInput::make('badge'),
                    TextInput::make('category')->required(),
                    self::lines('title'),
                    TextInput::make('location'),
                    Textarea::make('description')->rows(3),
                    self::lines('tags'),
                    TextInput::make('impact'),
                    self::lines('partners'),
                ])->columns(2),
            ],
            'feature_cards' => [
                TextInput::make('eyebrow')->required(),
                TextInput::make('heading_prefix'),
                TextInput::make('heading_highlight'),
                TextInput::make('heading_line2'),
                Textarea::make('subtext')->rows(2),
                Repeater::make('items')->schema([
                    self::image('photo'),
                    TextInput::make('category')->required(),
                    TextInput::make('date'),
                    TextInput::make('title')->required(),
                    TextInput::make('location'),
                    TextInput::make('stat_value'),
                    TextInput::make('stat_label'),
                    self::colour('stat_theme'),
                    Textarea::make('description')->rows(3),
                    self::lines('tags'),
                    TextInput::make('impact'),
                    self::lines('partners'),
                ])->columns(2),
            ],
            'logo_marquee' => [
                TextInput::make('eyebrow')->required(),
                TextInput::make('heading_prefix'),
                TextInput::make('heading_highlight'),
                self::lines('note'),
                Repeater::make('items')->schema([
                    self::image('logo'),
                    TextInput::make('name')->required(),
                ])->columns(2),
            ],
            'article_grid' => [
                TextInput::make('eyebrow')->required(),
                ...self::heading(),
                Textarea::make('subtext')->rows(2),
                Repeater::make('items')->minItems(1)->schema([
                    self::image('photo'),
                    TextInput::make('category')->required(),
                    TextInput::make('headline')->required(),
                    self::lines('paragraphs'),
                    TextInput::make('location'),
                ])->columns(2),
                self::segments('closing'),
            ],
            'events' => [
                TextInput::make('eyebrow')->required(),
                ...self::heading(),
                Textarea::make('subtext')->rows(2),
                TextInput::make('concluded_label'),
                Fieldset::make('Featured item')->statePath('featured')->schema([
                    self::image('photos')->multiple()->reorderable(),
                    TextInput::make('title')->required(),
                    TextInput::make('date'),
                    Textarea::make('description')->rows(3),
                    self::lines('stats'),
                ]),
                self::image('gallery')->multiple()->reorderable(),
                TextInput::make('upcoming_label'),
                Repeater::make('upcoming')->label('Upcoming items')->schema([
                    TextInput::make('month')->required(),
                    TextInput::make('day')->required(),
                    TextInput::make('category'),
                    TextInput::make('title')->required(),
                    TextInput::make('location'),
                ])->columns(5),
                TextInput::make('cta_label'),
            ],
            default => [],
        };
    }

    /** @return array<int, TextInput> */
    private static function heading(): array
    {
        return [
            TextInput::make('heading_prefix'),
            TextInput::make('heading_highlight')->helperText('Shown in the accent colour'),
            TextInput::make('heading_suffix'),
        ];
    }

    private static function image(string $name): FileUpload
    {
        return FileUpload::make($name)->disk('site')->image();
    }

    /** A repeater of plain strings, stored as a flat list. */
    private static function lines(string $name): Repeater
    {
        return Repeater::make($name)->simple(TextInput::make('line')->required());
    }

    private static function colour(string $name): Select
    {
        return Select::make($name)->options(['navy' => 'Navy', 'primary' => 'Primary'])->required();
    }

    private static function weight(): Select
    {
        return Select::make('weight')->options(['light' => 'Light', 'semibold' => 'Semibold'])->required();
    }

    private static function segments(string $name): Repeater
    {
        return Repeater::make($name)
            ->helperText('Each row is a run of text. A "Line break" row inserts a break on wide screens.')
            ->schema([
                Textarea::make('text')->rows(1),
                Toggle::make('highlight')->default(false),
                Toggle::make('break')->label('Line break')->default(false),
            ])
            ->columns(3);
    }

    private static function richBlock(string $name): Fieldset
    {
        return Fieldset::make(Str::headline($name))->statePath($name)->schema([
            TextInput::make('label')->required(),
            self::segments('segments'),
        ]);
    }
}
