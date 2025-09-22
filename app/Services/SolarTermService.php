<?php

namespace App\Services;

use LucNham\LunarCalendar\SolarTerm;
use LucNham\LunarCalendar\Terms\VnSolarTermIdentifier;
use LucNham\LunarCalendar\LunarDateTime;
use Illuminate\Support\Facades\Cache;

class SolarTermService
{
    /**
     * Lấy tiết khí hiện tại
     */
    public function getCurrentSolarTerm()
    {
        $cacheKey = 'solar_term_current_' . date('Y-m-d');
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () {
            $solarTerm = SolarTerm::now();
            $vnSolarTerm = SolarTerm::now(VnSolarTermIdentifier::class);
            
            return [
                'name' => $vnSolarTerm->name,
                'key' => $vnSolarTerm->key,
                'position' => $vnSolarTerm->position,
                'type' => $vnSolarTerm->type,
                'angle' => $vnSolarTerm->angle,
                'begin_timestamp' => $vnSolarTerm->begin,
                'begin_date' => date('Y-m-d H:i:s', $vnSolarTerm->begin),
            ];
        });
    }

    /**
     * Lấy tiết khí theo ngày
     */
    public function getSolarTermByDate($date)
    {
        // Chuyển đổi string thành DateTime nếu cần
        if (is_string($date)) {
            $dateTime = new \DateTime($date);
            $cacheKey = 'solar_term_date_' . $date;
        } else {
            $dateTime = $date;
            $cacheKey = 'solar_term_date_' . $date->format('Y-m-d');
        }
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($dateTime) {
            $solarTerm = SolarTerm::fromDate($dateTime);
            $vnSolarTerm = SolarTerm::fromDate($dateTime, VnSolarTermIdentifier::class);
            
            return [
                'name' => $vnSolarTerm->name,
                'key' => $vnSolarTerm->key,
                'position' => $vnSolarTerm->position,
                'type' => $vnSolarTerm->type,
                'angle' => $vnSolarTerm->angle,
                'begin_timestamp' => $vnSolarTerm->begin,
                'begin_date' => date('Y-m-d H:i:s', $solarTerm->begin),
            ];
        });
    }

    /**
     * Lấy tiết khí trước đó
     */
    public function getPreviousSolarTerm($date = null)
    {
        $solarTerm = $date ? SolarTerm::fromDate($date) : SolarTerm::now();
        $previous = $solarTerm->previous();
        $vnPrevious = SolarTerm::fromDate(
            date: new \DateTime(date('Y-m-d H:i:s', $previous->begin)),
            target: VnSolarTermIdentifier::class
        );
        
        return [
            'name' => $vnPrevious->name,
            'key' => $vnPrevious->key,
            'position' => $vnPrevious->position,
            'type' => $vnPrevious->type,
            'begin_date' => date('Y-m-d H:i:s', $previous->begin),
        ];
    }

    /**
     * Lấy tiết khí tiếp theo
     */
    public function getNextSolarTerm($date = null)
    {
        $solarTerm = $date ? SolarTerm::fromDate($date) : SolarTerm::now();
        $next = $solarTerm->next();
        $vnNext = SolarTerm::fromDate(
            date: new \DateTime(date('Y-m-d H:i:s', $next->begin)),
            target: VnSolarTermIdentifier::class
        );
        
        return [
            'name' => $vnNext->name,
            'key' => $vnNext->key,
            'position' => $vnNext->position,
            'type' => $vnNext->type,
            'begin_date' => date('Y-m-d H:i:s', $next->begin),
        ];
    }

    /**
     * Lấy danh sách tất cả tiết khí trong năm
     */
    public function getAllSolarTermsInYear($year)
    {
        $cacheKey = "solar_terms_year_{$year}";
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($year) {
            $solarTerms = [];
            
            // Bắt đầu từ Lập Xuân (tiết khí đầu tiên)
            $startDate = new \DateTime("$year-02-04");
            $solarTerm = SolarTerm::fromDate($startDate);
            
            for ($i = 0; $i < 24; $i++) {
                $vnSolarTerm = SolarTerm::fromDate(
                    date: new \DateTime(date('Y-m-d H:i:s', $solarTerm->begin)),
                    target: VnSolarTermIdentifier::class
                );
                
                $solarTerms[] = [
                    'position' => $vnSolarTerm->position,
                    'name' => $vnSolarTerm->name,
                    'key' => $vnSolarTerm->key,
                    'type' => $vnSolarTerm->type,
                    'begin_date' => date('Y-m-d H:i:s', $solarTerm->begin),
                    'begin_timestamp' => $solarTerm->begin,
                ];
                
                $solarTerm = $solarTerm->next();
            }
            
            return $solarTerms;
        });
    }

    /**
     * Lấy thông tin chi tiết về tiết khí
     */
    public function getSolarTermInfo($key)
    {
        $solarTerms = [
            'lap_xuan' => [
                'name' => 'Lập Xuân',
                'description' => 'Bắt đầu mùa xuân, cây cối đâm chồi nảy lộc',
                'date_range' => '4-5/2',
                'season' => 'Xuân',
            ],
            'vu_thuy' => [
                'name' => 'Vũ Thủy',
                'description' => 'Mưa xuân bắt đầu rơi, đất đai được tưới mát',
                'date_range' => '19-20/2',
                'season' => 'Xuân',
            ],
            'kinh_trap' => [
                'name' => 'Kinh Trập',
                'description' => 'Côn trùng thức dậy sau mùa đông',
                'date_range' => '5-6/3',
                'season' => 'Xuân',
            ],
            'xuan_phan' => [
                'name' => 'Xuân Phân',
                'description' => 'Ngày và đêm dài bằng nhau, chính giữa mùa xuân',
                'date_range' => '20-21/3',
                'season' => 'Xuân',
            ],
            'thanh_minh' => [
                'name' => 'Thanh Minh',
                'description' => 'Trời trong sáng, thời gian tảo mộ',
                'date_range' => '4-5/4',
                'season' => 'Xuân',
            ],
            'co_vu' => [
                'name' => 'Cốc Vũ',
                'description' => 'Mưa rào giúp lúa mọc tốt',
                'date_range' => '19-20/4',
                'season' => 'Xuân',
            ],
            'lap_ha' => [
                'name' => 'Lập Hạ',
                'description' => 'Bắt đầu mùa hè, thời tiết ấm áp',
                'date_range' => '5-6/5',
                'season' => 'Hạ',
            ],
            'tieu_man' => [
                'name' => 'Tiểu Mãn',
                'description' => 'Lúa bắt đầu trổ bông',
                'date_range' => '20-21/5',
                'season' => 'Hạ',
            ],
            'mang_chung' => [
                'name' => 'Mang Chủng',
                'description' => 'Lúa chín vàng, thời gian thu hoạch',
                'date_range' => '5-6/6',
                'season' => 'Hạ',
            ],
            'ha_chi' => [
                'name' => 'Hạ Chí',
                'description' => 'Ngày dài nhất trong năm',
                'date_range' => '21-22/6',
                'season' => 'Hạ',
            ],
            'tieu_thu' => [
                'name' => 'Tiểu Thử',
                'description' => 'Nóng bức bắt đầu',
                'date_range' => '6-7/7',
                'season' => 'Hạ',
            ],
            'dai_thu' => [
                'name' => 'Đại Thử',
                'description' => 'Nóng nhất trong năm',
                'date_range' => '22-23/7',
                'season' => 'Hạ',
            ],
            'lap_thu' => [
                'name' => 'Lập Thu',
                'description' => 'Bắt đầu mùa thu',
                'date_range' => '7-8/8',
                'season' => 'Thu',
            ],
            'xu_thu' => [
                'name' => 'Xử Thử',
                'description' => 'Hết nóng, thời tiết mát mẻ',
                'date_range' => '22-23/8',
                'season' => 'Thu',
            ],
            'bach_lo' => [
                'name' => 'Bạch Lộ',
                'description' => 'Sương mù xuất hiện vào sáng sớm',
                'date_range' => '7-8/9',
                'season' => 'Thu',
            ],
            'thu_phan' => [
                'name' => 'Thu Phân',
                'description' => 'Ngày và đêm dài bằng nhau, chính giữa mùa thu',
                'date_range' => '22-23/9',
                'season' => 'Thu',
            ],
            'han_lo' => [
                'name' => 'Hàn Lộ',
                'description' => 'Sương lạnh xuất hiện',
                'date_range' => '8-9/10',
                'season' => 'Thu',
            ],
            'suong_giang' => [
                'name' => 'Sương Giáng',
                'description' => 'Sương muối bắt đầu xuất hiện',
                'date_range' => '23-24/10',
                'season' => 'Thu',
            ],
            'lap_dong' => [
                'name' => 'Lập Đông',
                'description' => 'Bắt đầu mùa đông',
                'date_range' => '7-8/11',
                'season' => 'Đông',
            ],
            'tieu_tuyet' => [
                'name' => 'Tiểu Tuyết',
                'description' => 'Tuyết nhẹ bắt đầu rơi',
                'date_range' => '22-23/11',
                'season' => 'Đông',
            ],
            'dai_tuyet' => [
                'name' => 'Đại Tuyết',
                'description' => 'Tuyết rơi nhiều',
                'date_range' => '6-7/12',
                'season' => 'Đông',
            ],
            'dong_chi' => [
                'name' => 'Đông Chí',
                'description' => 'Ngày ngắn nhất trong năm',
                'date_range' => '21-22/12',
                'season' => 'Đông',
            ],
            'tieu_han' => [
                'name' => 'Tiểu Hàn',
                'description' => 'Lạnh bắt đầu',
                'date_range' => '5-6/1',
                'season' => 'Đông',
            ],
            'dai_han' => [
                'name' => 'Đại Hàn',
                'description' => 'Lạnh nhất trong năm',
                'date_range' => '19-20/1',
                'season' => 'Đông',
            ],
        ];

        return $solarTerms[$key] ?? null;
    }
}
