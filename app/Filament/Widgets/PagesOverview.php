<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Page;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;

/** The dashboard: every page with edit and view links, plus how the editor works. */
class PagesOverview extends Widget
{
    protected string $view = 'filament.widgets.pages-overview';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -1;

    protected static bool $isLazy = false;

    /** @return Collection<int, Page> */
    public function getPages(): Collection
    {
        return Page::query()->withCount('sections')->orderBy('title')->get();
    }
}
