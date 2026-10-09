<?php

declare(strict_types=1);

namespace App\Models;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
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

    /** Layouts that render a list of items and support `limit` + "View all". */
    public const LIST_TYPES = ['card_grid', 'feature_list', 'feature_cards', 'article_grid', 'events'];

    /** @var array<string, string> The data key holding each list layout's items. */
    public const ITEM_KEYS = [
        'card_grid' => 'items',
        'feature_list' => 'initiatives',
        'feature_cards' => 'items',
        'article_grid' => 'items',
        'events' => 'upcoming',
    ];

    /** Keys of config/home.php that hold site-wide settings (rows with no page). */
    public const SETTINGS = ['nav', 'social'];

    /**
     * Every item this list section can show: its own, or, when it points at a
     * source section on another page, the source's items ticked "Show on homepage".
     *
     * @return list<array<string, mixed>>
     */
    public function items(): array
    {
        $key = self::ITEM_KEYS[$this->type] ?? null;
        $sourceId = (int) ($this->data['source_section_id'] ?? 0);

        if ($key === null) {
            return [];
        }

        if ($sourceId === 0 || $sourceId === $this->id) {
            return array_values($this->data[$key] ?? []);
        }

        $source = self::query()->whereKey($sourceId)->where('type', $this->type)->first();

        return array_values(array_filter($source->data[$key] ?? [], fn (array $item): bool => ! empty($item['featured'])));
    }

    /**
     * The items actually rendered: the first `limit` (default 6). An empty or
     * zero limit shows everything, so legacy rows and listing pages stay complete.
     *
     * @return list<array<string, mixed>>
     */
    public function listItems(): array
    {
        $limit = array_key_exists('limit', $this->data) ? (int) $this->data['limit'] : 6;

        return $limit > 0 ? array_slice($this->items(), 0, $limit) : $this->items();
    }

    /** @param  array<string, mixed>  $item */
    public static function itemTitle(array $item): string
    {
        $title = $item['title'] ?? $item['headline'] ?? '';

        return is_array($title) ? implode(' ', $title) : (string) $title;
    }

    /**
     * Every list item on a page keyed by a URL slug that is unique on that page
     * (duplicate titles get -2, -3 …; a title with no slug-able characters becomes "item").
     *
     * @return array<string, array<string, mixed>>
     */
    public static function pageItems(Page $page): array
    {
        $items = [];

        foreach ($page->sections()->visible()->whereIn('type', self::LIST_TYPES)->get() as $section) {
            foreach ($section->items() as $item) {
                $base = Str::slug(self::itemTitle($item)) ?: 'item';
                $slug = $base;

                for ($n = 2; isset($items[$slug]); $n++) {
                    $slug = "{$base}-{$n}";
                }

                $items[$slug] = $item;
            }
        }

        return $items;
    }

    /**
     * The shared detail page for an item, or '' when it has no extra description.
     *
     * @param  array<string, mixed>  $item
     */
    public static function itemUrl(Page $page, array $item): string
    {
        if (self::details($item) === '') {
            return '';
        }

        $slug = array_search($item, self::pageItems($page), true);

        return $slug === false ? '' : route('page.item', [$page->slug, $slug]);
    }

    /**
     * The item's extra description as safe HTML. The DOM is rebuilt with an
     * allow-list of tags and no attributes except http(s)/mailto/relative hrefs
     * on links, so stored markup can never carry scripts or event handlers.
     * '' when there is no real text.
     *
     * @param  array<string, mixed>  $item
     */
    public static function details(array $item): string
    {
        $raw = (string) ($item['details'] ?? '');

        if (trim(str_replace("\u{A0}", ' ', html_entity_decode(strip_tags($raw)))) === '') {
            return '';
        }

        $document = new DOMDocument;
        $document->loadHTML('<?xml encoding="utf-8"?><body>'.$raw.'</body>', LIBXML_NOERROR | LIBXML_NOWARNING);
        $body = $document->getElementsByTagName('body')->item(0);

        return $body === null ? '' : self::cleanNodes($body);
    }

    private static function cleanNodes(DOMNode $parent): string
    {
        $allowed = ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'a', 'ul', 'ol', 'li', 'h2', 'h3', 'blockquote'];
        $html = '';

        foreach ($parent->childNodes as $node) {
            if ($node instanceof DOMText) {
                $html .= e($node->nodeValue);
            } elseif ($node instanceof DOMElement && in_array($node->tagName, $allowed, true)) {
                $href = $node->tagName === 'a' ? trim($node->getAttribute('href')) : '';
                $attributes = preg_match('#^(https?://|mailto:|/)#i', $href) ? ' href="'.e($href).'"' : '';
                $html .= $node->tagName === 'br'
                    ? '<br>'
                    : "<{$node->tagName}{$attributes}>".self::cleanNodes($node)."</{$node->tagName}>";
            } elseif ($node instanceof DOMElement && ! in_array($node->tagName, ['script', 'style'], true)) {
                $html .= self::cleanNodes($node);
            }
        }

        return $html;
    }

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
