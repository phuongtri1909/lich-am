<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Feature;
use App\Models\Product;
use App\Models\Industry;
use App\Models\ImageHome;
use App\Models\BannerHome;
use App\Models\BannerPage;
use App\Models\DressStyle;
use App\Models\IntroImage;
use App\Models\ProductView;
use App\Models\IntroFeature;
use App\Models\ReviewRating;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use App\Models\IntroLocation;
use App\Models\SlideLocation;
use App\Models\VisionMission;
use App\Models\FeatureSection;
use App\Models\ProductVariant;
use App\Models\GeneralIntroduction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        return view('client.pages.home');
    }
}
