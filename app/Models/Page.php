<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $title
 * @property string $slug
 * @property ?string $meta_title
 * @property ?string $meta_description
 * @property bool $is_published
 */
class Page extends Model
{
    protected $fillable = ['title', 'slug', 'meta_title', 'meta_description', 'is_published'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    /** @return HasMany<Section, $this> */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->ordered();
    }
}
