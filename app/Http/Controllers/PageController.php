<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class PageController extends Controller
{
    public function show(string $slug = 'home'): View
    {
        $page = $this->page($slug);

        return view('page', [
            'page' => $page,
            'site' => $this->site(),
            'sections' => $page->sections()->visible()->get(),
        ]);
    }

    /** One shared layout renders any list item that has extra details. */
    public function item(string $slug, string $item): View
    {
        $page = $this->page($slug);
        $found = Section::pageItems($page)[$item] ?? null;

        abort_if($found === null || Section::details($found) === '', 404);

        return view('item', ['page' => $page, 'site' => $this->site(), 'item' => $found]);
    }

    private function page(string $slug): Page
    {
        return Page::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();
    }

    /** @return Collection<string, array<string, mixed>> */
    private function site(): Collection
    {
        return Section::query()->whereNull('page_id')->visible()->pluck('data', 'type');
    }
}
