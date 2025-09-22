<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SocialController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MetaTagController;


Route::group(['as' => 'admin.'], function () {
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return 'Cache cleared';
    })->name('clear.cache');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        // SEO Management
        Route::resource('seo', SeoController::class)->except(['show', 'create', 'store', 'destroy']);

        // Meta Tags Management
        Route::resource('meta-tags', MetaTagController::class);

        Route::get('socials', [SocialController::class, 'index'])->name('socials.index');
        Route::post('socials', [SocialController::class, 'store'])->name('socials.store');
        Route::put('socials/{social}', [SocialController::class, 'update'])->name('socials.update');
        Route::delete('socials/{social}', [SocialController::class, 'destroy'])->name('socials.destroy');

        Route::resource('accounts', AccountController::class)->except(['show']);
    });

    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', function () {
            return view('admin.pages.auth.login');
        })->name('login');

        Route::post('login', [AuthController::class, 'login'])->name('login.post');
    });
});
