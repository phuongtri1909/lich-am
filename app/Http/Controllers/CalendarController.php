<?php

namespace App\Http\Controllers;

use App\Services\LunarCalendarService;
use App\Services\SolarTermService;
use App\Services\SexagenaryService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class CalendarController extends Controller
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

    public function show($year, $month, $day)
    {
        // Validate date
        if (!checkdate($month, $day, $year)) {
            abort(404, 'Ngày không hợp lệ');
        }

        // Set SEO
        $this->seoService->setDaySeo($year, $month, $day);

        $selectedDate = "$year-$month-" . sprintf('%02d', $day);
        
        // Lấy thông tin lịch âm cho ngày được chọn
        $lunarInfo = $this->lunarCalendarService->getLunarInfoForDate($selectedDate);
        
        // Lấy lịch tháng hiện tại
        $monthCalendar = $this->lunarCalendarService->getMonthCalendar($year, $month);
        
        // Lấy thông tin tiết khí cho ngày được chọn
        $solarTerm = $this->solarTermService->getSolarTermByDate($selectedDate);
        
        // Lấy thông tin can chi cho ngày được chọn
        $sexagenary = $this->sexagenaryService->getSexagenaryForDate($selectedDate);
        
        // Lấy giờ hoàng đạo từ URL
        $auspiciousHours = $this->lunarCalendarService->getAuspiciousHours($year, $month, $day);
        
        // Lấy thông tin ngày tốt xấu từ URL
        $dayFortune = $this->lunarCalendarService->getDayFortune($year, $month, $day);
        
        // Lấy chi tiết xem tốt xấu từ URL
        $dayDetail = $this->lunarCalendarService->crawlDayDetail($year, $month, $day);
        $vncalDetail = $dayDetail['vncal_detail'] ?? '';

        return view('client.pages.calendar-day', compact(
            'lunarInfo',
            'monthCalendar', 
            'solarTerm',
            'sexagenary',
            'auspiciousHours',
            'dayFortune',
            'vncalDetail',
            'selectedDate',
            'year',
            'month',
            'day'
        ));
    }

    public function month($year, $month)
    {
        // Validate month
        if ($month < 1 || $month > 12) {
            abort(404, 'Tháng không hợp lệ');
        }

        // Set SEO
        $this->seoService->setMonthSeo($year, $month);

        // Lấy lịch tháng
        $monthCalendar = $this->lunarCalendarService->getMonthCalendar($year, $month);
        
        // Lấy thông tin lịch âm hiện tại
        $lunarInfo = $this->lunarCalendarService->getCurrentLunarInfo();
        
        // Lấy thông tin tiết khí hiện tại
        $solarTerm = $this->solarTermService->getCurrentSolarTerm();
        
        // Lấy thông tin can chi hiện tại
        $sexagenary = $this->sexagenaryService->getCurrentSexagenary();
        
        // Lấy thông tin tháng từ URL
        $monthInfo = $this->lunarCalendarService->crawlMonthInfo($year, $month);
        $monthMainText = '';
        $goodDays = [];
        $badDays = [];
        $solarHolidays = [];
        $historicalEvents = [];
        
        if ($monthInfo['success'] && isset($monthInfo['data'])) {
            $monthMainText = $monthInfo['data']['main_text'] ?? '';
            $goodDays = array_column($monthInfo['data']['good_days'] ?? [], 'day');
            $badDays = array_column($monthInfo['data']['bad_days'] ?? [], 'day');
            $solarHolidays = $monthInfo['data']['solar_holidays'] ?? [];
            $historicalEvents = $monthInfo['data']['historical_events'] ?? [];
        }

        return view('client.pages.calendar-month', compact(
            'lunarInfo',
            'monthCalendar', 
            'solarTerm',
            'sexagenary',
            'monthMainText',
            'goodDays',
            'badDays',
            'solarHolidays',
            'historicalEvents',
            'year',
            'month'
        ));
    }

    public function year($year)
    {
        // Validate year
        if ($year < 1900 || $year > 2100) {
            abort(404, 'Năm không hợp lệ');
        }

        // Set SEO
        $this->seoService->setYearSeo($year);

        // Lấy lịch cho tất cả 12 tháng
        $yearCalendars = [];
        for ($month = 1; $month <= 12; $month++) {
            $yearCalendars[$month] = $this->lunarCalendarService->getMonthCalendar($year, $month);
        }
        
        // Lấy thông tin lịch âm hiện tại
        $lunarInfo = $this->lunarCalendarService->getCurrentLunarInfo();
        
        // Lấy thông tin tiết khí hiện tại
        $solarTerm = $this->solarTermService->getCurrentSolarTerm();
        
        // Lấy thông tin can chi hiện tại
        $sexagenary = $this->sexagenaryService->getCurrentSexagenary();
        
        // Lấy giờ hoàng đạo từ URL (sử dụng tháng và ngày hiện tại)
        $currentMonth = date('m');
        $currentDay = date('d');
        $auspiciousHours = $this->lunarCalendarService->getAuspiciousHours($year, $currentMonth, $currentDay);
        
        // Lấy thông tin ngày tốt xấu từ URL (sử dụng tháng và ngày hiện tại)
        $dayFortune = $this->lunarCalendarService->getDayFortune($year, $currentMonth, $currentDay);

        // Lấy thông tin năm từ URL
        $yearInfo = $this->lunarCalendarService->crawlLunarYearInfo($year);
        $yearMainText = '';
        
        if ($yearInfo['success'] && isset($yearInfo['data']['main_text'])) {
            $yearMainText = $yearInfo['data']['main_text'];
        }

        // Lấy ngày lễ từ URL
        $holidaysData = $this->lunarCalendarService->crawlHolidaysFromUrl($year);
        $solarHolidays = $holidaysData['solar_holidays'] ?? [];
        $lunarHolidays = $holidaysData['lunar_holidays'] ?? [];

        // Lấy tháng và ngày hiện tại
        $currentMonth = date('m');
        $currentDay = date('d');

        return view('client.pages.calendar-year', compact(
            'lunarInfo',
            'yearCalendars', 
            'solarTerm',
            'sexagenary',
            'auspiciousHours',
            'dayFortune',
            'yearMainText',
            'solarHolidays',
            'lunarHolidays',
            'currentMonth',
            'currentDay',
            'year'
        ));
    }

    public function converter(Request $request)
    {
        // Set SEO
        $this->seoService->setConverterSeo();

        // Get date from request parameter or use current date
        $dateParam = $request->get('date');
        if ($dateParam) {
            try {
                $date = new \DateTime($dateParam);
                $year = $date->format('Y');
                $month = $date->format('m');
                $day = $date->format('d');
                $currentDate = $dateParam;
            } catch (\Exception $e) {
                $year = date('Y');
                $month = date('m');
                $day = date('d');
                $currentDate = date('Y-m-d');
            }
        } else {
            $year = date('Y');
            $month = date('m');
            $day = date('d');
            $currentDate = date('Y-m-d');
        }

        // Get lunar info for the specified date
        $lunarInfo = $this->lunarCalendarService->getLunarInfoForDate($currentDate);
        $solarTerm = $this->solarTermService->getSolarTermByDate($currentDate);
        $sexagenary = $this->sexagenaryService->getSexagenaryForDate($currentDate);
        $dayFortune = $this->lunarCalendarService->getDayFortune($year, $month, $day);
        $auspiciousHours = $this->lunarCalendarService->getAuspiciousHours($year, $month, $day);
        $monthCalendar = $this->lunarCalendarService->getMonthCalendar($year, $month);

        return view('client.pages.converter', compact(
            'year', 
            'month', 
            'day', 
            'currentDate',
            'lunarInfo',
            'solarTerm',
            'sexagenary',
            'dayFortune',
            'auspiciousHours',
            'monthCalendar'
        ));
    }

    public function convert(Request $request)
    {
        $request->validate([
            'date' => 'required|string',
            'type' => 'required|in:gregorian_to_lunar,lunar_to_gregorian'
        ]);

        $date = $request->input('date');
        $type = $request->input('type');

        try {
            if ($type === 'gregorian_to_lunar') {
                // Validate gregorian date format
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                    throw new \Exception('Định dạng ngày dương lịch không hợp lệ. Sử dụng định dạng YYYY-MM-DD');
                }
                
                // Chuyển từ dương lịch sang âm lịch
                $lunarInfo = $this->lunarCalendarService->getLunarInfoForDate($date);
                $solarTerm = $this->solarTermService->getSolarTermByDate($date);
                $sexagenary = $this->sexagenaryService->getSexagenaryForDate($date);
                
                return response()->json([
                    'success' => true,
                    'result' => [
                        'gregorian_date' => $date,
                        'lunar_date' => $lunarInfo['lunar_date'],
                        'lunar_date_full' => $lunarInfo['lunar_date_full'],
                        'day_of_week' => $lunarInfo['day_of_week'],
                        'solar_term' => $solarTerm,
                        'sexagenary' => $sexagenary,
                        'is_leap_month' => $lunarInfo['is_leap_month']
                    ]
                ]);
            } else {
                // Validate lunar date format
                if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
                    throw new \Exception('Định dạng ngày âm lịch không hợp lệ. Sử dụng định dạng DD-MM-YYYY');
                }
                
                // Chuyển từ âm lịch sang dương lịch
                // Parse lunar date (format: dd-mm-yyyy)
                $lunarParts = explode('-', $date);
                if (count($lunarParts) !== 3) {
                    throw new \Exception('Định dạng ngày âm lịch không hợp lệ');
                }
                
                $lunarDay = (int)$lunarParts[0];
                $lunarMonth = (int)$lunarParts[1];
                $lunarYear = (int)$lunarParts[2];
                
                // Tạo LunarDateTime từ âm lịch
                $lunar = \LucNham\LunarCalendar\LunarDateTime::create($lunarYear, $lunarMonth, $lunarDay);
                $gregorianDate = $lunar->toDateTimeString();
                
                // Lấy thông tin bổ sung
                $lunarInfo = $this->lunarCalendarService->getLunarInfoForDate($gregorianDate);
                $solarTerm = $this->solarTermService->getSolarTermByDate($gregorianDate);
                $sexagenary = $this->sexagenaryService->getSexagenaryForDate($gregorianDate);
                
                return response()->json([
                    'success' => true,
                    'result' => [
                        'lunar_date' => $date,
                        'gregorian_date' => date('Y-m-d', strtotime($gregorianDate)),
                        'gregorian_date_full' => $gregorianDate,
                        'day_of_week' => $lunarInfo['day_of_week'],
                        'solar_term' => $solarTerm,
                        'sexagenary' => $sexagenary,
                        'is_leap_month' => $lunarInfo['is_leap_month']
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
