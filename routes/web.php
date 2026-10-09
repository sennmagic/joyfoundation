<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'show'])->name('home');
Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '[a-z0-9-]+')->name('page');
Route::get('/{slug}/{item}', [PageController::class, 'item'])->where(['slug' => '[a-z0-9-]+', 'item' => '[a-z0-9-]+'])->name('page.item');
