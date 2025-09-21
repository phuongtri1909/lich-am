<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SocialController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CkeditorController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LogoSiteController;
use App\Http\Controllers\Admin\FranchiseController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BannerHomeController;
use App\Http\Controllers\Admin\ImageHomeController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\DressStyleController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\CategoryBlogController;
use App\Http\Controllers\Admin\IntroFeatureController;
use App\Http\Controllers\Admin\IntroLocationController;
use App\Http\Controllers\Admin\SlideLocationController;
use App\Http\Controllers\Admin\IntroImageController;
use App\Http\Controllers\Admin\VisionMissionController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectMediaController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\BannerPageController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\FeatureSectionController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\FranchiseContactController;
use App\Http\Controllers\Admin\GeneralIntroductionController;
use App\Http\Controllers\Admin\ContactSubmissionController as AdminContactSubmissionController;

Route::group(['as' => 'admin.'], function () {
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return 'Cache cleared';
    })->name('clear.cache');

    Route::group(['middleware' => ['auth', 'check.role:admin']], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('logo-site', [LogoSiteController::class, 'edit'])->name('logo-site.edit');
        Route::put('logo-site', [LogoSiteController::class, 'update'])->name('logo-site.update');

        // Language Management
        Route::get('languages', [LanguageController::class, 'index'])->name('languages.index');
        Route::get('languages/get', [LanguageController::class, 'getLanguageContent'])->name('languages.get');
        Route::post('languages/update', [LanguageController::class, 'updateLanguageContent'])->name('languages.update');
        Route::post('languages/create', [LanguageController::class, 'createLanguageFile'])->name('languages.create');

        Route::resource('category-blogs', CategoryBlogController::class)->except(['show']);
        Route::resource('blogs', BlogController::class)->except(['show']);

        Route::post('blogs/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.upload.image');
        Route::get('blogs/load-categories', [BlogController::class, 'loadCategories'])->name('blogs.load-categories');

        Route::resource('banners', BannerController::class)->except(['show']);

        Route::resource('socials', SocialController::class)->except(['show']);

        // Contacts management
        Route::get('contacts', [AdminContactSubmissionController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [AdminContactSubmissionController::class, 'show'])->name('contacts.show');
        Route::delete('contacts/{contact}', [AdminContactSubmissionController::class, 'destroy'])->name('contacts.destroy');
        Route::post('contacts/export', [AdminContactSubmissionController::class, 'export'])->name('contacts.export');


        Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])
            ->name('ckeditor.upload');

        Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('setting/order', [SettingController::class, 'updateOrder'])->name('setting.update.order');
        Route::put('setting/smtp', [SettingController::class, 'updateSMTP'])->name('setting.update.smtp');
        Route::put('setting/google', [SettingController::class, 'updateGoogle'])->name('setting.update.google');
        Route::put('setting/paypal', [SettingController::class, 'updatePaypal'])->name('setting.update.paypal');

        // Banner Homes Management
        Route::resource('banner-homes', BannerHomeController::class);

        // General Introduction Management
        Route::get('general-introductions', [GeneralIntroductionController::class, 'index'])->name('general-introductions.index');
        Route::get('general-introductions/create', [GeneralIntroductionController::class, 'create'])->name('general-introductions.create');
        Route::post('general-introductions', [GeneralIntroductionController::class, 'store'])->name('general-introductions.store');
        Route::get('general-introductions/{generalIntroduction}/edit', [GeneralIntroductionController::class, 'edit'])->name('general-introductions.edit');
        Route::put('general-introductions/{generalIntroduction}', [GeneralIntroductionController::class, 'update'])->name('general-introductions.update');

        // Intro Features Management
        Route::resource('intro-features', IntroFeatureController::class)->except(['show']);
        Route::resource('intro-locations', IntroLocationController::class)->except(['show']);
        Route::resource('intro-images', IntroImageController::class)->except(['show']);
        Route::resource('intro-images-about', IntroImageController::class)->except(['show'])->names([
            'index' => 'intro-images-about.index',
            'create' => 'intro-images-about.create',
            'store' => 'intro-images-about.store',
            'edit' => 'intro-images-about.edit',
            'update' => 'intro-images-about.update',
            'destroy' => 'intro-images-about.destroy'
        ]);
        Route::resource('vision-missions', VisionMissionController::class)->except(['show']);
        Route::resource('industries', IndustryController::class)->except(['show']);
        Route::resource('projects', ProjectController::class)->except(['show']);

        // Project Media Management
        Route::prefix('projects/{project}')->name('projects.media.')->group(function () {
            Route::get('media', [ProjectMediaController::class, 'index'])->name('index');
            Route::get('media/create', [ProjectMediaController::class, 'create'])->name('create');
            Route::post('media', [ProjectMediaController::class, 'store'])->name('store');
            Route::get('media/{media}/edit', [ProjectMediaController::class, 'edit'])->name('edit');
            Route::put('media/{media}', [ProjectMediaController::class, 'update'])->name('update');
            Route::delete('media/{media}', [ProjectMediaController::class, 'destroy'])->name('destroy');
            Route::post('media/order', [ProjectMediaController::class, 'updateOrder'])->name('order');
        });
        Route::resource('slide-locations', SlideLocationController::class)->except(['show']);
        Route::resource('slide-locations-about', SlideLocationController::class)->except(['show'])->names([
            'index' => 'slide-locations-about.index',
            'create' => 'slide-locations-about.create',
            'store' => 'slide-locations-about.store',
            'edit' => 'slide-locations-about.edit',
            'update' => 'slide-locations-about.update',
            'destroy' => 'slide-locations-about.destroy'
        ]);
        // Image Homes Management
        Route::resource('image-homes', ImageHomeController::class)->except(['show']);
        Route::resource('image-homes-about', ImageHomeController::class)->except(['show'])->names([
            'index' => 'image-homes-about.index',
            'create' => 'image-homes-about.create',
            'store' => 'image-homes-about.store',
            'edit' => 'image-homes-about.edit',
            'update' => 'image-homes-about.update',
            'destroy' => 'image-homes-about.destroy'
        ]);

        // Features Management
        Route::resource('features', FeatureController::class)->except(['show']);

        // Gallery Management
        Route::resource('galleries', GalleryController::class)->except(['show']);

        // Banner Page Management
        Route::get('banner-pages/edit', [BannerPageController::class, 'edit'])->name('banner-pages.edit');
        Route::put('banner-pages/update/{bannerPage}', [BannerPageController::class, 'update'])->name('banner-pages.update');

        // Account Management
        Route::resource('accounts', AccountController::class)->except(['show']);

        // SEO Management
        Route::resource('seo', SeoController::class)->except(['show', 'create', 'store']);
    });

    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', function () {
            return view('admin.pages.auth.login');
        })->name('login');

        Route::post('login', [AuthController::class, 'login'])->name('login.post');
    });
});
