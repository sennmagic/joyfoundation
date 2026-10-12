<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('contact', fn (Request $request): Limit => Limit::perMinute(5)
            ->by($request->ip())
            ->response(fn (Request $request, array $headers): RedirectResponse => redirect()
                ->to(route('page', ['slug' => 'contact']).'#contact-form')
                ->withHeaders($headers)
                ->withInput($request->only(['name', 'email', 'topic', 'message']))
                ->withErrors(['submission' => 'Please wait one minute before sending another message. Your text has been kept below.'])));
    }
}
