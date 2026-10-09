<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', PageController::class)->name('home');
Route::get('/{slug}', PageController::class)->where('slug', '[a-z0-9-]+')->name('page');
