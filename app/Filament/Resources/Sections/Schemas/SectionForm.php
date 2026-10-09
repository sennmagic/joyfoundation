<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sections\Schemas;

use App\Models\Page;
use App\Models\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

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
                    ->visibleOn('create')
                    ->label('Layout'),
                Image::make(fn (Get $get): string => self::previewUrl((string) $get('type')), 'Section preview')
                    ->key('preview')
                    ->imageWidth('100%')
                    ->visible(fn (Get $get, string $operation): bool => $operation === 'create' && self::hasPreview((string) $get('type'))),
                Toggle::make('is_visible')->label('Visible on the page')->default(true),
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
                self::items('items')->schema([
                    TextInput::make('label')->required(),
                    self::link('href'),
                    self::items('children', 'Dropdown items')->schema([
                        TextInput::make('label')->required(),
                        self::link('href'),
                    ])->columns(2)->columnSpanFull()->default([]),
                ])->columns(2),
            ],
            'social' => [
                self::items('items')->schema([
                    TextInput::make('label')->required(),
                    TextInput::make('url')->required(),
                    self::image('icon'),
                ])->columns(3),
            ],
            'hero' => [
                self::image('background'),
                self::heading(),
            ],
            'text_band' => [
                Textarea::make('text')->rows(3)->required(),
            ],
            'image_text' => [
                self::image('photo'),
                self::image('photo_polaroid'),
                TextInput::make('badge_label')->helperText('Small text above the badge value, e.g. "EST."'),
                TextInput::make('badge_value')->helperText('Leave empty to hide the badge'),
                TextInput::make('eyebrow')->required(),
                TextInput::make('heading_prefix'),
                TextInput::make('heading_highlight'),
                self::lines('paragraphs'),
                TextInput::make('cta_label')->required(),
            ],
            'statements' => [
                TextInput::make('eyebrow')->required(),
                self::heading(),
                Textarea::make('intro')->rows(2),
                self::items('blocks', 'Statements')->schema([
                    TextInput::make('label')->required(),
                    self::segments('segments'),
                ]),
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
                self::items('stats', 'Stats')->schema([
                    TextInput::make('value')->required(),
                    self::lines('caption'),
                    self::colour('color'),
                    self::weight(),
                ])->columns(2),
                self::items('dark_stats', 'Dark bar stats')->schema([
                    TextInput::make('value')->required(),
                    self::weight(),
                    TextInput::make('caption')->required(),
                    TextInput::make('sub_caption'),
                ])->columns(2),
            ],
            'card_grid' => [
                TextInput::make('eyebrow')->required(),
                self::heading(),
                Textarea::make('subtext')->rows(2),
                self::source('card_grid'),
                self::items('items')->hidden(fn (Get $get): bool => filled($get('source_section_id')))->schema([
                    self::featured(),
                    self::image('photo'),
                    self::image('icon'),
                    TextInput::make('category')->required(),
                    TextInput::make('title')->required(),
                    Textarea::make('description')->rows(3),
                    self::details(),
                ])->columns(2),
                self::listControls(),
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
                self::source('feature_list'),
                self::items('initiatives')->hidden(fn (Get $get): bool => filled($get('source_section_id')))->schema([
                    self::featured(),
                    self::image('photo'),
                    TextInput::make('badge'),
                    TextInput::make('category')->required(),
                    self::lines('title'),
                    TextInput::make('location'),
                    Textarea::make('description')->rows(3),
                    self::lines('tags'),
                    TextInput::make('impact'),
                    self::lines('partners'),
                    self::details(),
                ])->columns(2),
                self::listControls(),
            ],
            'feature_cards' => [
                TextInput::make('eyebrow')->required(),
                TextInput::make('heading_prefix'),
                TextInput::make('heading_highlight'),
                TextInput::make('heading_line2'),
                Textarea::make('subtext')->rows(2),
                self::source('feature_cards'),
                self::items('items')->hidden(fn (Get $get): bool => filled($get('source_section_id')))->schema([
                    self::featured(),
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
                    self::details(),
                ])->columns(2),
                self::listControls(),
            ],
            'logo_marquee' => [
                TextInput::make('eyebrow')->required(),
                TextInput::make('heading_prefix'),
                TextInput::make('heading_highlight'),
                self::lines('note'),
                self::items('items')->schema([
                    self::image('logo'),
                    TextInput::make('name')->required(),
                ])->columns(2),
            ],
            'article_grid' => [
                TextInput::make('eyebrow')->required(),
                self::heading(),
                Textarea::make('subtext')->rows(2),
                self::source('article_grid'),
                self::items('items')->hidden(fn (Get $get): bool => filled($get('source_section_id')))->schema([
                    self::featured(),
                    self::image('photo'),
                    TextInput::make('category')->required(),
                    TextInput::make('headline')->required(),
                    self::lines('paragraphs'),
                    TextInput::make('location'),
                    self::details(),
                ])->columns(2),
                self::listControls(),
                self::segments('closing'),
            ],
            'events' => [
                TextInput::make('eyebrow')->required(),
                self::heading(),
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
                self::source('events'),
                self::items('upcoming', 'Upcoming items')->hidden(fn (Get $get): bool => filled($get('source_section_id')))->schema([
                    self::featured(),
                    TextInput::make('month')->required(),
                    TextInput::make('day')->required(),
                    TextInput::make('category'),
                    TextInput::make('title')->required(),
                    TextInput::make('location'),
                    self::details(),
                ])->columns(5),
                self::listControls(),
            ],
            default => [],
        };
    }

    /** The three parts of a section heading, side by side. */
    private static function heading(): Fieldset
    {
        return Fieldset::make('Heading')
            ->columns(3)
            ->schema([
                TextInput::make('heading_prefix')->label('Before'),
                TextInput::make('heading_highlight')->label('Highlighted word')->helperText('Shown in the accent colour'),
                TextInput::make('heading_suffix')->label('After'),
            ]);
    }

    /** A URL field that suggests every published page. */
    private static function link(string $name, bool $required = true): TextInput
    {
        return TextInput::make($name)
            ->required($required)
            ->datalist(fn (): array => Page::query()->where('is_published', true)->orderBy('title')->pluck('slug')->map(fn (string $slug): string => $slug === 'home' ? '/' : "/{$slug}")->all());
    }

    /** Lists show at most `limit` items; the rest live behind a "View all" link. */
    private static function listControls(): FormSection
    {
        return FormSection::make('How many to show')
            ->description('Show the first few items here and send visitors to a full list for the rest.')
            ->collapsed()
            ->columns(3)
            ->schema([
                TextInput::make('limit')->integer()->minValue(0)->default(6)->label('Items to show')->helperText('Leave empty to show all'),
                TextInput::make('view_all_label')->default('View all')->label('Button text'),
                self::link('view_all_href', required: false)->label('Button link')->helperText('Shown only when there are more items than the limit'),
            ]);
    }

    /**
     * Lets a list section on one page (usually the homepage) show items that are
     * written and ticked on another page, instead of keeping its own copy.
     */
    private static function source(string $type): Select
    {
        return Select::make('source_section_id')
            ->label('Take items from')
            ->placeholder("This section's own items")
            ->options(fn (): array => Section::query()
                ->where('type', $type)
                ->whereNotNull('page_id')
                ->with('page')
                ->get()
                ->mapWithKeys(fn (Section $section): array => [$section->id => $section->page->title.' › '.Section::label($type)])
                ->all())
            ->helperText('Pick a section on another page. Only its items ticked "Show on homepage" appear here.')
            ->live();
    }

    /** Tick on an item so a section elsewhere that takes items from this one shows it. */
    private static function featured(): Toggle
    {
        return Toggle::make('featured')->label('Show on homepage')->default(true)->columnSpanFull();
    }

    /** A list of editable items: collapsible rows named after their title. */
    private static function items(string $name, string $label = 'Items'): Repeater
    {
        return Repeater::make($name)
            ->label($label)
            ->collapsible()
            ->collapsed()
            ->itemLabel(function (array $state): ?string {
                $title = $state['title'] ?? $state['headline'] ?? $state['label'] ?? $state['name'] ?? $state['value'] ?? null;

                if (is_array($title)) {
                    $title = implode(' ', array_filter($title, 'is_scalar'));
                }

                return is_scalar($title) && trim((string) $title) !== '' ? (string) $title : null;
            })
            ->addActionLabel('Add item');
    }

    /** Extra copy that gives the item its own detail page (shared layout). */
    private static function details(): RichEditor
    {
        return RichEditor::make('details')
            ->label('Detail page content')
            ->helperText('When filled, the item gets a "Learn More" link to its own page.')
            ->columnSpanFull();
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
}
