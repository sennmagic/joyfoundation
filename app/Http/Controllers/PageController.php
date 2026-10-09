<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function __invoke(string $slug = 'home'): View
    {
        $page = Page::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('page', [
            'page' => $page,
            'site' => Section::query()->whereNull('page_id')->visible()->pluck('data', 'type'),
            'sections' => $page->sections()->visible()->get(),
        ]);
    }
}
