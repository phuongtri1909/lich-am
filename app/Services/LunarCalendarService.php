<?php

namespace App\Services;

use LucNham\LunarCalendar\LunarDateTime;
use LucNham\LunarCalendar\SolarTerm;
use LucNham\LunarCalendar\Sexagenary;
use LucNham\LunarCalendar\Terms\VnSolarTermIdentifier;
use LucNham\LunarCalendar\Terms\VnStemIdentifier;
use LucNham\LunarCalendar\Terms\VnBranchIdentifier;
use Illuminate\Support\Facades\Cache;

class LunarCalendarService
{
    /**
     * Lấy thông tin lịch âm hiện tại
     */
    public function getCurrentLunarInfo()
    {
        $cacheKey = 'lunar_info_current_' . date('Y-m-d');
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () {
            $lunar = new LunarDateTime();
            $solarTerm = SolarTerm::now();
            $vnSolarTerm = SolarTerm::now(VnSolarTermIdentifier::class);
            
            $sexagenary = new Sexagenary($lunar);
            $vnSexagenary = new Sexagenary(
                lunar: $lunar,
                stemIdetifier: VnStemIdentifier::class,
                branchIdentifier: VnBranchIdentifier::class,
            );

            // Sử dụng timestamp hiện tại thay vì timestamp của lunar
            $currentTimestamp = time();

            return [
                'lunar_date' => $lunar->format('d-m-Y'),
                'lunar_date_full' => $lunar->format('d-m-Y H:i:s'),
                'gregorian_date' => $lunar->toDateTimeString(),
                'day_of_week' => $this->getVietnameseDayOfWeek($currentTimestamp), // Sử dụng timestamp hiện tại
                'solar_term' => [
                    'name' => $vnSolarTerm->name,
                    'key' => $vnSolarTerm->key,
                    'type' => $vnSolarTerm->type,
                ],
                'sexagenary' => [
                    'day' => $vnSexagenary->format('[D+]'),
                    'month' => $vnSexagenary->format('[M+]'),
                    'year' => $vnSexagenary->format('[Y+]'),
                    'hour' => $vnSexagenary->format('[H+]'),
                ],
                'lunar_month_days' => $lunar->format('t'),
                'is_leap_month' => $lunar->format('L') !== $lunar->format('l'),
            ];
        });
    }

    /**
     * Lấy lịch tháng âm hiện tại
     */
    public function getCurrentMonthCalendar()
    {
        // Lấy tháng dương lịch hiện tại
        $currentYear = date('Y');
        $currentMonth = date('m');

        
        return $this->getMonthCalendar($currentYear, $currentMonth);
    }

    /**
     * Lấy lịch tháng dương lịch theo năm và tháng
     */
    public function getMonthCalendar($year, $month)
    {
        $cacheKey = "month_calendar_{$year}_{$month}";
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($year, $month) {
            $calendar = [];
            $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
            
            // Lấy thông tin ngày tốt/xấu từ URL
            $monthInfo = $this->crawlMonthInfo($year, $month);
            $goodDays = [];
            $badDays = [];
            
            if ($monthInfo['success'] && isset($monthInfo['data'])) {
                $goodDays = array_column($monthInfo['data']['good_days'] ?? [], 'day');
                $badDays = array_column($monthInfo['data']['bad_days'] ?? [], 'day');
            }
        
        // Lấy ngày đầu tiên của tháng và tính toán ngày bắt đầu của tuần
        $firstDayOfMonth = date('w', mktime(0, 0, 0, $month, 1, $year));
        $firstDayOfMonth = $firstDayOfMonth == 0 ? 7 : $firstDayOfMonth; // Chuyển Chủ nhật (0) thành 7
        
        // Thêm các ngày của tháng trước
        $prevMonth = $month == 1 ? 12 : $month - 1;
        $prevYear = $month == 1 ? $year - 1 : $year;
        $daysInPrevMonth = date('t', mktime(0, 0, 0, $prevMonth, 1, $prevYear));
        
        for ($i = 1; $i < $firstDayOfMonth; $i++) {
            $day = $daysInPrevMonth - $firstDayOfMonth + $i + 1;
            $gregorianDate = "$prevYear-$prevMonth-" . sprintf('%02d', $day);
            $gregorianDateTime = new \DateTime($gregorianDate);
            
            // Chuyển đổi sang âm lịch
            $lunar = LunarDateTime::fromGregorian($gregorianDate);
            $lunarDay = $lunar->format('j');
            
            $sexagenary = new Sexagenary($lunar);
            $vnSexagenary = new Sexagenary(
                lunar: $lunar,
                stemIdetifier: VnStemIdentifier::class,
                branchIdentifier: VnBranchIdentifier::class,
            );

            $calendar[] = [
                'gregorian_day' => $day,
                'lunar_day' => $lunarDay,
                'lunar_month' => $lunar->format('m'),
                'lunar_year' => $lunar->format('Y'),
                'gregorian_date' => $gregorianDate,
                'day_of_week' => $this->getVietnameseDayOfWeek($gregorianDateTime->getTimestamp()),
                'sexagenary' => $vnSexagenary->format('[D+]'),
                'is_today' => false,
                'is_good_day' => false, // Ngày tháng trước không tính
                'is_bad_day' => false, // Ngày tháng trước không tính
                'is_prev_month' => true,
            ];
        }
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $gregorianDate = "$year-$month-" . sprintf('%02d', $day);
            $gregorianDateTime = new \DateTime($gregorianDate);
            
            // Chuyển đổi sang âm lịch
            $lunar = LunarDateTime::fromGregorian($gregorianDate);
            $lunarDay = $lunar->format('j');
            $lunarMonth = $lunar->format('m');
            $lunarYear = $lunar->format('Y');
            
            $sexagenary = new Sexagenary($lunar);
            $vnSexagenary = new Sexagenary(
                lunar: $lunar,
                stemIdetifier: VnStemIdentifier::class,
                branchIdentifier: VnBranchIdentifier::class,
            );

            $calendar[] = [
                'gregorian_day' => $day,
                'lunar_day' => $lunarDay,
                'lunar_month' => $lunarMonth,
                'lunar_year' => $lunarYear,
                'gregorian_date' => $gregorianDate,
                'day_of_week' => $this->getVietnameseDayOfWeek($gregorianDateTime->getTimestamp()),
                'sexagenary' => $vnSexagenary->format('[D+]'),
                'is_today' => $this->isTodayGregorian($gregorianDate),
                'is_good_day' => in_array($day, $goodDays), // Sử dụng data từ URL
                'is_bad_day' => in_array($day, $badDays), // Sử dụng data từ URL
                'is_current_month' => true,
            ];
        }

        // Thêm các ngày của tháng sau để lấp đầy lưới 5 hàng (35 ô)
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        $totalCells = count($calendar);
        $remainingCells = 35 - $totalCells; // 5 tuần x 7 ngày = 35 ô
        
        if ($remainingCells > 0) {
            for ($day = 1; $day <= $remainingCells; $day++) {
                $gregorianDate = "$nextYear-$nextMonth-" . sprintf('%02d', $day);
                $gregorianDateTime = new \DateTime($gregorianDate);
                
                // Chuyển đổi sang âm lịch
                $lunar = LunarDateTime::fromGregorian($gregorianDate);
                $lunarDay = $lunar->format('j');
                
                $sexagenary = new Sexagenary($lunar);
                $vnSexagenary = new Sexagenary(
                    lunar: $lunar,
                    stemIdetifier: VnStemIdentifier::class,
                    branchIdentifier: VnBranchIdentifier::class,
                );

                $calendar[] = [
                    'gregorian_day' => $day,
                    'lunar_day' => $lunarDay,
                    'lunar_month' => $lunar->format('m'),
                    'lunar_year' => $lunar->format('Y'),
                    'gregorian_date' => $gregorianDate,
                    'day_of_week' => $this->getVietnameseDayOfWeek($gregorianDateTime->getTimestamp()),
                    'sexagenary' => $vnSexagenary->format('[D+]'),
                    'is_today' => false,
                    'is_good_day' => false, // Ngày tháng sau không tính
                    'is_bad_day' => false, // Ngày tháng sau không tính
                    'is_next_month' => true,
                ];
            }
        }

            return $calendar;
        });
    }

    /**
     * Chuyển đổi từ dương lịch sang âm lịch
     */
    public function gregorianToLunar($gregorianDate)
    {
        $lunar = LunarDateTime::fromGregorian($gregorianDate);
        $vnSexagenary = new Sexagenary(
            lunar: $lunar,
            stemIdetifier: VnStemIdentifier::class,
            branchIdentifier: VnBranchIdentifier::class,
        );

        return [
            'lunar_date' => $lunar->format('d-m-Y'),
            'lunar_date_full' => $lunar->format('d-m-Y H:i:s'),
            'sexagenary' => $vnSexagenary->format('[D+]'),
            'is_leap_month' => $lunar->format('L') !== $lunar->format('l'),
        ];
    }

    /**
     * Chuyển đổi từ âm lịch sang dương lịch
     */
    public function lunarToGregorian($lunarDate)
    {
        $lunar = new LunarDateTime($lunarDate);
        return $lunar->toDateTimeString();
    }

    /**
     * Lấy giờ hoàng đạo từ URL
     */
    public function getAuspiciousHours($year, $month, $day)
    {
        try {
            $dayDetail = $this->crawlDayDetail($year, $month, $day);
            $hoangDao = $dayDetail['hoang_dao'] ?? 'Không có thông tin';
            
            // Parse string thành array
            if (is_string($hoangDao) && !empty($hoangDao)) {
                // Split by comma hoặc semicolon và clean up
                $hours = preg_split('/[,;]/', $hoangDao);
                $hours = array_map('trim', $hours);
                $hours = array_filter($hours); // Remove empty elements
                
                // Nếu không có data từ URL, fallback về default
                if (empty($hours)) {
                    return $this->getDefaultAuspiciousHours();
                }
                
                return $hours;
            }
            
            return $this->getDefaultAuspiciousHours();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to get auspicious hours', [
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'error' => $e->getMessage()
            ]);
            return $this->getDefaultAuspiciousHours();
        }
    }

    /**
     * Lấy giờ hoàng đạo mặc định
     */
    private function getDefaultAuspiciousHours()
    {
        return [
            'Sửu (1-3h)',
            'Thìn (7-9h)', 
            'Ngọ (11-13h)',
            'Mùi (13-15h)',
            'Tuất (19-21h)',
            'Hợi (21-23h)',
        ];
    }

    /**
     * Lấy thông tin ngày tốt xấu từ URL
     */
    public function getDayFortune($year, $month, $day)
    {
        try {
            $dayDetail = $this->crawlDayDetail($year, $month, $day);

            $thienDao = $dayDetail['thien_dao'] ?? null;
            
            if (is_array($thienDao)) {
                return [
                    'fortune' => $thienDao['type'],
                    'description' => $thienDao['description']
                ];
            }
            
            return [
                'fortune' => 'Không có thông tin',
                'description' => 'Không có thông tin'
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to get day fortune', [
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'error' => $e->getMessage()
            ]);
            return [
                'fortune' => 'Không có thông tin',
                'description' => 'Không có thông tin'
            ];
        }
    }

    /**
     * Lấy tên ngày trong tuần bằng tiếng Việt
     */
    private function getVietnameseDayOfWeek($timestamp)
    {
        $days = [
            'Chủ Nhật',
            'Thứ Hai', 
            'Thứ Ba',
            'Thứ Tư',
            'Thứ Năm',
            'Thứ Sáu',
            'Thứ Bảy'
        ];
        
        return $days[date('w', $timestamp)];
    }

    /**
     * Kiểm tra có phải ngày hôm nay không
     */
    private function isToday($lunarDate)
    {
        $today = new LunarDateTime();
        return $lunarDate->format('Y-m-d') === $today->format('Y-m-d');
    }

    /**
     * Kiểm tra có phải ngày hôm nay không (dương lịch)
     */
    private function isTodayGregorian($gregorianDate)
    {
        $today = date('Y-m-d');
        return $gregorianDate === $today;
    }


    /**
     * Lấy thông tin lịch âm cho ngày cụ thể
     */
    public function getLunarInfoForDate($gregorianDate)
    {
        $cacheKey = 'lunar_info_date_' . $gregorianDate;
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($gregorianDate) {
            $lunar = LunarDateTime::fromGregorian($gregorianDate);
            $dateTime = new \DateTime($gregorianDate);
            $solarTerm = SolarTerm::fromDate($dateTime);
            $vnSolarTerm = SolarTerm::fromDate($dateTime, VnSolarTermIdentifier::class);
            
            $sexagenary = new Sexagenary($lunar);
            $vnSexagenary = new Sexagenary(
                lunar: $lunar,
                stemIdetifier: VnStemIdentifier::class,
                branchIdentifier: VnBranchIdentifier::class,
            );

            return [
                'lunar_date' => $lunar->format('d-m-Y'),
                'lunar_date_full' => $lunar->format('d-m-Y H:i:s'),
                'gregorian_date' => $lunar->toDateTimeString(),
                'day_of_week' => $this->getVietnameseDayOfWeek($dateTime->getTimestamp()), // Sử dụng timestamp của ngày dương lịch
                'solar_term' => [
                    'name' => $vnSolarTerm->name,
                    'key' => $vnSolarTerm->key,
                    'type' => $vnSolarTerm->type,
                ],
                'sexagenary' => [
                    'day' => $vnSexagenary->format('[D+]'),
                    'month' => $vnSexagenary->format('[M+]'),
                    'year' => $vnSexagenary->format('[Y+]'),
                    'hour' => $vnSexagenary->format('[H+]'),
                ],
                'lunar_month_days' => $lunar->format('t'),
                'is_leap_month' => $lunar->format('L') !== $lunar->format('l'),
            ];
        });
    }

    /**
     * Crawl day detail from URL
     */
    public function crawlDayDetail($year, $month = null, $day = null, $url = null)
    {
        $cacheKey = "day_detail_{$year}_{$month}_{$day}";
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($year, $month, $day, $url) {
            try {
                if ($url === null) {
                    if ($month === null || $day === null) {
                        throw new \Exception('Month and day are required when URL is not provided');
                    }
                    $url = "https://www.xemlicham.com/am-lich/nam/{$year}/thang/{$month}/ngay/{$day}";
                }

            try {
                $response = \Illuminate\Support\Facades\Http::timeout(30)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'vi-VN,vi;q=0.9,en;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate',
                        'Connection' => 'keep-alive',
                        'Upgrade-Insecure-Requests' => '1',
                    ])
                    ->withOptions([
                        'verify' => false,
                        'curl' => [
                            CURLOPT_ENCODING => '',
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_MAXREDIRS => 5,
                        ]
                    ])
                    ->get($url);

                if (!$response->successful()) {
                    throw new \Exception("HTTP request failed with status: {$response->status()}");
                }

                $html = $response->body();

            } catch (\Exception $httpException) {
                \Illuminate\Support\Facades\Log::warning("HTTP client failed, trying file_get_contents", [
                    'url' => $url,
                    'error' => $httpException->getMessage()
                ]);

                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => [
                            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            'Accept-Language: vi-VN,vi;q=0.9,en;q=0.8',
                            'Accept-Encoding: gzip, deflate',
                            'Connection: keep-alive',
                            'Upgrade-Insecure-Requests: 1',
                        ],
                        'timeout' => 30,
                        'follow_location' => true,
                        'max_redirects' => 5,
                    ]
                ]);

                $html = file_get_contents($url, false, $context);

                if ($html === false) {
                    throw new \Exception('Failed to fetch content using file_get_contents');
                }
            }

            // Parse the HTML content
            return $this->parseDayDetailFromHtml($html);

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to crawl day detail from URL', [
                    'url' => $url,
                    'error' => $e->getMessage()
                ]);

                throw new \Exception('Failed to crawl day detail from URL: ' . $e->getMessage());
            }
        });
    }

    /**
     * Parse detailed day information from HTML
     */
    private function parseDayDetailFromHtml($html)
    {
        $data = [
            'gregorian_date' => '',
            'lunar_date' => '',
            'day_of_week' => '',
            'can_chi' => '',
            'thien_dao' => '',
            'hoang_dao' => '',
            'vncal_detail' => ''
        ];

        // Extract Gregorian date
        if (preg_match('/Ngày <b>Dương Lịch<\/b>: (\d+-\d+-\d+)/i', $html, $matches)) {
            $data['gregorian_date'] = $matches[1];
        }

        // Extract Lunar date
        if (preg_match('/Ngày <b>Âm Lịch<\/b>:\s*(\d+-\d+-\d+)/i', $html, $matches)) {
            $data['lunar_date'] = $matches[1];
        }

        // Extract day of week
        if (preg_match('/Ngày trong tuần: <b>(.*?)<\/b>/i', $html, $matches)) {
            $data['day_of_week'] = trim($matches[1]);
        }

        // Extract Can Chi
        if (preg_match('/Ngày <b>(.*?)<\/b> tháng <b>(.*?)<\/b> năm <b>(.*?)<\/b>/i', $html, $matches)) {
            $data['can_chi'] = [
                'day' => trim($matches[1]),
                'month' => trim($matches[2]),
                'year' => trim($matches[3])
            ];
        }

        // Extract Thiên Đạo
        if (preg_match('/<div class="col-md-6">.*?<div class="col-md-6">.*?<p>Ngày <b>(.*?)<\/b>: (.*?)<\/p>/is', $html, $matches)) {
            $dayType = trim($matches[1]);
            $description = trim(strip_tags($matches[2]));

            $data['thien_dao'] = [
                'type' => $dayType,
                'description' => $description
            ];
        }

        // Extract Hoàng Đạo
        if (preg_match('/<div class="col-md-6">.*?<div class="col-md-6">.*?<p>Giờ <b>Hoàng Đạo<\/b>: (.*?)<\/p>/is', $html, $matches)) {
            $data['hoang_dao'] = trim(strip_tags($matches[1]));
        }

        // Extract vncal-detail section
        if (preg_match('/<div class="vncal-detail"[^>]*>(.*?)<\/div>\s*<\/div>/is', $html, $matches)) {
            $vncalHtml = $matches[1];

            // Remove Google ads and iframes
            $vncalHtml = preg_replace('/<div[^>]*class="[^"]*google-auto-placed[^"]*"[^>]*>.*?<\/div>/is', '', $vncalHtml);
            $vncalHtml = preg_replace('/<ins[^>]*class="[^"]*adsbygoogle[^"]*"[^>]*>.*?<\/ins>/is', '', $vncalHtml);
            $vncalHtml = preg_replace('/<iframe[^>]*>.*?<\/iframe>/is', '', $vncalHtml);

            // Clean up extra whitespace but preserve table structure
            $vncalHtml = preg_replace('/\s+/', ' ', $vncalHtml);
            $vncalHtml = preg_replace('/>\s+</', '><', $vncalHtml);
            $vncalHtml = trim($vncalHtml);

            $data['vncal_detail'] = $vncalHtml;
        }

        return $data;
    }

    /**
     * Crawl month information from URL
     */
    public function crawlMonthInfo($year, $month)
    {
        $cacheKey = "month_info_{$year}_{$month}";
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($year, $month) {
            try {
                $url = "https://www.xemlicham.com/am-lich/nam/{$year}/thang/{$month}";

            try {
                $response = \Illuminate\Support\Facades\Http::timeout(30)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'vi-VN,vi;q=0.9,en;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate',
                        'Connection' => 'keep-alive',
                        'Upgrade-Insecure-Requests' => '1',
                    ])
                    ->withOptions([
                        'verify' => false,
                        'curl' => [
                            CURLOPT_ENCODING => '',
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_MAXREDIRS => 5,
                        ]
                    ])
                    ->get($url);

                if (!$response->successful()) {
                    throw new \Exception("HTTP request failed with status: {$response->status()}");
                }

                $html = $response->body();

            } catch (\Exception $httpException) {
                \Illuminate\Support\Facades\Log::warning("HTTP client failed, trying file_get_contents", [
                    'url' => $url,
                    'error' => $httpException->getMessage()
                ]);

                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => implode("\r\n", [
                            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            'Accept-Language: vi-VN,vi;q=0.9,en;q=0.8',
                            'Accept-Encoding: gzip, deflate',
                            'Connection: keep-alive',
                            'Upgrade-Insecure-Requests: 1',
                        ]),
                        'timeout' => 30,
                        'ignore_errors' => true,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]);

                $html = file_get_contents($url, false, $context);

                if ($html === false) {
                    throw new \Exception("Failed to fetch content using file_get_contents");
                }
            }

            // Parse the HTML content
            $data = $this->parseMonthContent($html, $year, $month);

            return [
                'success' => true,
                'url' => $url,
                'year' => $year,
                'month' => $month,
                'data' => $data,
                'crawled_at' => now()->toDateTimeString()
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("LunarCalendarService::crawlMonthInfo failed", [
                'year' => $year,
                'month' => $month,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

                return [
                    'success' => false,
                    'url' => $url ?? null,
                    'year' => $year,
                    'month' => $month,
                    'error' => $e->getMessage(),
                    'crawled_at' => now()->toDateTimeString()
                ];
            }
        });
    }

    /**
     * Parse month content from HTML
     */
    private function parseMonthContent($html, $year, $month)
    {
        $data = [
            'main_text' => '',
            'good_days' => [], // Hoàng Đạo - ngày tốt
            'bad_days' => [],   // Hắc Đạo - ngày xấu
            'solar_holidays' => [], // Ngày lễ dương lịch tháng
            'historical_events' => [] // Sự kiện lịch sử tháng
        ];

        // Extract main-text content
        if (preg_match('/<p[^>]*class="[^"]*main-text[^"]*"[^>]*>(.*?)<\/p>/is', $html, $matches)) {
            $data['main_text'] = trim(strip_tags($matches[1]));
        }

        // Extract good days (Hoàng Đạo)
        if (preg_match('/<h4>Ngày tốt tháng \d+ \(Hoàng Đạo\)<\/h4>.*?<ul[^>]*class="[^"]*nav nav-pills ul-related month tot[^"]*"[^>]*>(.*?)<\/ul>/is', $html, $matches)) {
            $goodDaysHtml = $matches[1];

            if (preg_match_all('/<li><a[^>]*href="[^"]*\/ngay\/(\d+)"[^>]*>.*?<\/a><\/li>/is', $goodDaysHtml, $dayMatches)) {
                foreach ($dayMatches[1] as $day) {
                    $data['good_days'][] = [
                        'year_id' => $year,
                        'month_id' => $month,
                        'day' => (int)$day
                    ];
                }
            }
        }

        // Extract bad days (Hắc Đạo)
        if (preg_match('/<h4>Ngày xấu tháng \d+ \(Hắc Đạo\)<\/h4>.*?<ul[^>]*class="[^"]*nav nav-pills ul-related month xau[^"]*"[^>]*>(.*?)<\/ul>/is', $html, $matches)) {
            $badDaysHtml = $matches[1];

            if (preg_match_all('/<li><a[^>]*href="[^"]*\/ngay\/(\d+)"[^>]*>.*?<\/a><\/li>/is', $badDaysHtml, $dayMatches)) {
                foreach ($dayMatches[1] as $day) {
                    $data['bad_days'][] = [
                        'year_id' => $year,
                        'month_id' => $month,
                        'day' => (int)$day
                    ];
                }
            }
        }

        // Extract solar holidays (Ngày lễ dương lịch tháng)
        if (preg_match('/<h4>Ngày lễ dương lịch tháng \d+<\/h4>.*?<ul[^>]*class="[^"]*list-content[^"]*"[^>]*>(.*?)<\/ul>/is', $html, $matches)) {
            $holidaysHtml = $matches[1];

            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $holidaysHtml, $holidayMatches)) {
                foreach ($holidayMatches[1] as $holiday) {
                    $holiday = trim(strip_tags($holiday));
                    if (!empty($holiday)) {
                        $data['solar_holidays'][] = $holiday;
                    }
                }
            }
        }

        // Extract historical events (Sự kiện lịch sử tháng)
        if (preg_match('/<h4>Sự kiện lịch sử tháng \d+<\/h4>.*?<ul[^>]*class="[^"]*list-content[^"]*"[^>]*>(.*?)<\/ul>/is', $html, $matches)) {
            $eventsHtml = $matches[1];

            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $eventsHtml, $eventMatches)) {
                foreach ($eventMatches[1] as $event) {
                    $event = trim(strip_tags($event));
                    if (!empty($event)) {
                        $data['historical_events'][] = $event;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Crawl holidays data from URL
     */
    public function crawlHolidaysFromUrl($year)
    {
        $cacheKey = "holidays_{$year}";
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($year) {
            try {
                $url = "https://www.xemlicham.com/am-lich/nam/{$year}";

                $response = \Illuminate\Support\Facades\Http::timeout(30)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'vi-VN,vi;q=0.9,en;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate',
                        'Connection' => 'keep-alive',
                        'Upgrade-Insecure-Requests' => '1',
                    ])
                    ->withOptions([
                        'verify' => false,
                        'curl' => [
                            CURLOPT_ENCODING => '',
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_MAXREDIRS => 5,
                        ]
                    ])
                    ->get($url);

                if (!$response->successful()) {
                    throw new \Exception("HTTP request failed with status: {$response->status()}");
                }

                $html = $response->body();

            } catch (\Exception $httpException) {
                \Illuminate\Support\Facades\Log::warning("HTTP client failed, trying file_get_contents", [
                    'error' => $httpException->getMessage(),
                    'url' => $url
                ]);

                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => implode("\r\n", [
                            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            'Accept-Language: vi-VN,vi;q=0.9,en;q=0.8',
                            'Accept-Encoding: gzip, deflate',
                            'Connection: keep-alive',
                            'Upgrade-Insecure-Requests: 1',
                        ]),
                        'timeout' => 30,
                        'ignore_errors' => true,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]);

                $html = file_get_contents($url, false, $context);

                if ($html === false) {
                    throw new \Exception("Failed to fetch content using file_get_contents");
                }
            }

            return $this->parseHolidaysFromHtml($html);

            \Illuminate\Support\Facades\Log::error("LunarCalendarService::crawlHolidaysFromUrl failed", [
                'year' => $year,
                'error' => $e->getMessage()
            ]);

            return [
                'solar_holidays' => [],
                'lunar_holidays' => [],
                'error' => $e->getMessage()
            ];
        });
    }

    /**
     * Parse holidays data from HTML
     */
    private function parseHolidaysFromHtml($html)
    {
        $holidays = [
            'solar_holidays' => [], // Dương lịch
            'lunar_holidays' => []  // Âm lịch
        ];

        // Extract solar holidays (Ngày lễ dương lịch)
        if (preg_match('/<h3>Ngày lễ dương lịch[^<]*<\/h3>.*?<ul[^>]*class="[^"]*list-content[^"]*"[^>]*>(.*?)<\/ul>/is', $html, $matches)) {
            $solarHtml = $matches[1];

            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $solarHtml, $holidayMatches)) {
                foreach ($holidayMatches[1] as $holiday) {
                    $holiday = trim(strip_tags($holiday));
                    if (!empty($holiday)) {
                        $parsedHoliday = $this->parseHolidayString($holiday, false);
                        if ($parsedHoliday) {
                            $holidays['solar_holidays'][] = $parsedHoliday;
                        }
                    }
                }
            }
        }

        // Extract lunar holidays (Ngày lễ âm lịch)
        if (preg_match('/<h3>Ngày lễ âm lịch[^<]*<\/h3>.*?<ul[^>]*class="[^"]*list-content[^"]*"[^>]*>(.*?)<\/ul>/is', $html, $matches)) {
            $lunarHtml = $matches[1];

            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $lunarHtml, $holidayMatches)) {
                foreach ($holidayMatches[1] as $holiday) {
                    $holiday = trim(strip_tags($holiday));
                    if (!empty($holiday)) {
                        $parsedHoliday = $this->parseHolidayString($holiday, true);
                        if ($parsedHoliday) {
                            $holidays['lunar_holidays'][] = $parsedHoliday;
                        }
                    }
                }
            }
        }

        return $holidays;
    }

    /**
     * Parse individual holiday string
     */
    private function parseHolidayString($holidayString, $isLunar)
    {
        // Pattern: "1/1: Tết Dương lịch." or "15/1: Tết Nguyên Tiêu (Lễ Thượng Nguyên)."
        if (preg_match('/^(\d+)\/(\d+):\s*(.+?)\.?$/i', $holidayString, $matches)) {
            $day = (int)$matches[1];
            $month = (int)$matches[2];
            $name = trim($matches[3]);

            return [
                'name' => $name,
                'day' => $day,
                'month' => $month,
                'is_lunar' => $isLunar ? 1 : 0
            ];
        }

        return null;
    }

    /**
     * Crawl lunar year information from URL
     */
    public function crawlLunarYearInfo($year)
    {
        $cacheKey = "lunar_year_info_{$year}";
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($year) {
            try {
                $url = "https://www.xemlicham.com/am-lich/nam/{$year}";

                $response = \Illuminate\Support\Facades\Http::timeout(30)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'vi-VN,vi;q=0.9,en;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate',
                        'Connection' => 'keep-alive',
                        'Upgrade-Insecure-Requests' => '1',
                    ])
                    ->withOptions([
                        'verify' => false,
                        'curl' => [
                            CURLOPT_ENCODING => '',
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_MAXREDIRS => 5,
                        ]
                    ])
                    ->get($url);

                if (!$response->successful()) {
                    throw new \Exception("HTTP request failed with status: {$response->status()}");
                }

                $html = $response->body();

            } catch (\Exception $httpException) {
                \Illuminate\Support\Facades\Log::warning("HTTP client failed, trying file_get_contents", [
                    'error' => $httpException->getMessage(),
                    'url' => $url
                ]);

                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => implode("\r\n", [
                            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                            'Accept-Language: vi-VN,vi;q=0.9,en;q=0.8',
                            'Accept-Encoding: gzip, deflate',
                            'Connection: keep-alive',
                            'Upgrade-Insecure-Requests: 1',
                        ]),
                        'timeout' => 30,
                        'ignore_errors' => true,
                    ],
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]);

                $html = file_get_contents($url, false, $context);

                if ($html === false) {
                    throw new \Exception("Failed to fetch content using file_get_contents");
                }
            }

            // Parse the HTML content
            $data = $this->parseLunarYearContent($html, $year);

            return [
                'success' => true,
                'url' => $url,
                'year' => $year,
                'data' => $data,
                'crawled_at' => now()->toDateTimeString()
            ];

            \Illuminate\Support\Facades\Log::error("LunarCalendarService::crawlLunarYearInfo failed", [
                'year' => $year,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'url' => $url ?? null,
                'year' => $year,
                'error' => $e->getMessage(),
                'crawled_at' => now()->toDateTimeString()
            ];
        });
    }

    /**
     * Parse lunar year content from HTML
     */
    private function parseLunarYearContent($html, $year)
    {
        $data = [
            'main_text' => '',
            'solar_holidays' => [],
            'lunar_holidays' => []
        ];

        // Extract main-text content
        if (preg_match('/<p[^>]*class="[^"]*main-text[^"]*"[^>]*>(.*?)<\/p>/is', $html, $matches)) {
            $data['main_text'] = trim(strip_tags($matches[1]));
        }

        // Extract solar holidays (Ngày lễ dương lịch)
        if (preg_match('/<h3>Ngày lễ dương lịch<\/h3>.*?<ul[^>]*class="[^"]*list-content[^"]*"[^>]*>(.*?)<\/ul>/is', $html, $matches)) {
            $holidaysHtml = $matches[1];

            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $holidaysHtml, $holidayMatches)) {
                foreach ($holidayMatches[1] as $holiday) {
                    $holiday = trim(strip_tags($holiday));
                    if (!empty($holiday)) {
                        $data['solar_holidays'][] = $holiday;
                    }
                }
            }
        }

        // Extract lunar holidays (Ngày lễ âm lịch)
        if (preg_match('/<h3>Ngày lễ âm lịch<\/h3>.*?<ul[^>]*class="[^"]*list-content[^"]*"[^>]*>(.*?)<\/ul>/is', $html, $matches)) {
            $holidaysHtml = $matches[1];

            if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $holidaysHtml, $holidayMatches)) {
                foreach ($holidayMatches[1] as $holiday) {
                    $holiday = trim(strip_tags($holiday));
                    if (!empty($holiday)) {
                        $data['lunar_holidays'][] = $holiday;
                    }
                }
            }
        }

        return $data;
    }
}
