<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property ?int $page_id
 * @property string $type
 * @property array<string, mixed> $data
 * @property int $sort_order
 * @property bool $is_visible
 */
class Section extends Model
{
    /** Section layouts, in homepage order. Names describe the layout, not the content. */
    public const TYPES = ['hero', 'text_band', 'image_text', 'statements', 'stats', 'card_grid', 'feature_list', 'feature_cards', 'logo_marquee', 'article_grid', 'events'];

    /** @var array<string, string> Picker label per layout. */
    public const LABELS = [
        'hero' => 'Hero Banner',
        'text_band' => 'Text Band',
        'image_text' => 'Image + Text',
        'statements' => 'Statements',
        'stats' => 'Statistics',
        'card_grid' => 'Card Grid',
        'feature_list' => 'Feature List',
        'feature_cards' => 'Feature Cards',
        'logo_marquee' => 'Logo Marquee',
        'article_grid' => 'Article Grid',
        'events' => 'Events',
        'nav' => 'Navigation',
        'social' => 'Social Links',
    ];

    /** @var array<string, string> Which config/home.php block seeds each layout. */
    public const CONFIG_KEYS = [
        'hero' => 'hero',
        'text_band' => 'intro',
        'image_text' => 'story',
        'statements' => 'mission_section',
        'stats' => 'impact',
        'card_grid' => 'programs',
        'feature_list' => 'long_term_community',
        'feature_cards' => 'achievements',
        'logo_marquee' => 'partners',
        'article_grid' => 'stories',
        'events' => 'events',
    ];

    /** Keys of config/home.php that hold site-wide settings (rows with no page). */
    public const SETTINGS = ['nav', 'social'];

    public static function label(string $type): string
    {
        return self::LABELS[$type] ?? Str::headline($type);
    }

    protected $fillable = ['page_id', 'type', 'data', 'sort_order', 'is_visible'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_visible' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Section $section): void {
            if ($section->page_id !== null && $section->sort_order === null) {
                $section->sort_order = (int) Section::query()->where('page_id', $section->page_id)->max('sort_order') + 1;
            }
        });
    }

    /** @return BelongsTo<Page, $this> */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /** @param Builder<Section> $query */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    /** @param Builder<Section> $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
