<?php

namespace App\Http\Controllers;

use App\Services\LunarCalendarService;
use App\Services\SolarTermService;
use App\Services\SexagenaryService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $lunarCalendarService;
    protected $solarTermService;
    protected $sexagenaryService;
    protected $seoService;

    public function __construct(
        LunarCalendarService $lunarCalendarService,
        SolarTermService $solarTermService,
        SexagenaryService $sexagenaryService,
        SeoService $seoService
    ) {
        $this->lunarCalendarService = $lunarCalendarService;
        $this->solarTermService = $solarTermService;
        $this->sexagenaryService = $sexagenaryService;
        $this->seoService = $seoService;
    }

    public function index(Request $request)
    {
        // Set SEO
        $this->seoService->setHomeSeo();

        // Lấy thông tin lịch âm hiện tại
        $lunarInfo = $this->lunarCalendarService->getCurrentLunarInfo();
        
        // Lấy lịch tháng hiện tại
        $monthCalendar = $this->lunarCalendarService->getCurrentMonthCalendar();
        
        // Lấy thông tin tiết khí hiện tại
        $solarTerm = $this->solarTermService->getCurrentSolarTerm();
        
        // Lấy thông tin can chi hiện tại
        $sexagenary = $this->sexagenaryService->getCurrentSexagenary();
        
        // Lấy giờ hoàng đạo từ URL
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentDay = date('d');
        $auspiciousHours = $this->lunarCalendarService->getAuspiciousHours($currentYear, $currentMonth, $currentDay);
        
        // Lấy thông tin ngày tốt xấu từ URL
        $dayFortune = $this->lunarCalendarService->getDayFortune($currentYear, $currentMonth, $currentDay);
        
        // Lấy chi tiết xem tốt xấu từ URL
        $dayDetail = $this->lunarCalendarService->crawlDayDetail($currentYear, $currentMonth, $currentDay);
        $vncalDetail = $dayDetail['vncal_detail'] ?? '';

        return view('client.pages.home', compact(
            'lunarInfo',
            'monthCalendar', 
            'solarTerm',
            'sexagenary',
            'auspiciousHours',
            'dayFortune',
            'vncalDetail'
        ));
    }
}
