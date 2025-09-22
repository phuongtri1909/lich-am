<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SitemapController;
use Artesaos\SEOTools\Facades\SEOTools;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/calendar/{year}/{month}/{day}', [CalendarController::class, 'show'])->name('calendar.day');
Route::get('/calendar/{year}/{month}', [CalendarController::class, 'month'])->name('calendar.month');
Route::get('/calendar/{year}', [CalendarController::class, 'year'])->name('calendar.year');

// Date converter routes
Route::get('/converter', [CalendarController::class, 'converter'])->name('converter');
Route::post('/converter/convert', [CalendarController::class, 'convert'])->name('converter.convert');

// Sitemap routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-index.xml', [SitemapController::class, 'sitemapIndex'])->name('sitemap.index.main');
Route::get('/sitemap-years.xml', [SitemapController::class, 'index'])->name('sitemap.years');
Route::get('/sitemap/{year}.xml', [SitemapController::class, 'year'])->name('sitemap.year');
Route::get('/sitemap/{year}/{month}.xml', [SitemapController::class, 'month'])->name('sitemap.month');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
