<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoSetting;

class SeoSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seoSettings = [
            [
                'page_key' => 'home',
                'title' => 'Lịch âm hôm nay - Xem lịch âm chính xác nhất',
                'description' => 'Xem lịch âm hôm nay, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt Nam. Công cụ tra cứu lịch âm dương đầy đủ và miễn phí.',
                'keywords' => 'lịch âm, lịch âm hôm nay, giờ hoàng đạo, ngày tốt xấu, âm lịch, lịch vạn niên, can chi, tiết khí',
                'is_active' => true,
            ],
            [
                'page_key' => 'converter',
                'title' => 'Chuyển đổi ngày âm dương - Công cụ đổi lịch chính xác',
                'description' => 'Công cụ chuyển đổi ngày dương lịch sang âm lịch và ngược lại chính xác nhất cho người Việt Nam. Đổi lịch nhanh chóng và miễn phí.',
                'keywords' => 'đổi ngày âm dương, chuyển đổi lịch, dương lịch sang âm lịch, âm lịch sang dương lịch, công cụ đổi lịch',
                'is_active' => true,
            ],
            [
                'page_key' => 'calendar_year',
                'title' => 'Lịch âm năm {YEAR} - Xem lịch vạn niên năm {YEAR}',
                'description' => 'Xem lịch âm năm {YEAR}, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt Nam. Lịch vạn niên năm {YEAR} đầy đủ.',
                'keywords' => 'lịch âm năm {YEAR}, lịch vạn niên năm {YEAR}, giờ hoàng đạo năm {YEAR}, ngày tốt xấu năm {YEAR}, âm lịch năm {YEAR}',
                'is_active' => true,
            ],
            [
                'page_key' => 'calendar_month',
                'title' => 'Lịch âm tháng {MONTH} năm {YEAR} - Xem lịch vạn niên tháng {MONTH}/{YEAR}',
                'description' => 'Xem lịch âm tháng {MONTH} năm {YEAR}, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt Nam. Lịch vạn niên tháng {MONTH}/{YEAR}.',
                'keywords' => 'lịch âm tháng {MONTH} năm {YEAR}, lịch vạn niên tháng {MONTH}/{YEAR}, giờ hoàng đạo tháng {MONTH}, ngày tốt xấu tháng {MONTH}, âm lịch tháng {MONTH}',
                'is_active' => true,
            ],
            [
                'page_key' => 'calendar_day',
                'title' => 'Lịch âm ngày {DAY} tháng {MONTH} năm {YEAR} - Xem lịch vạn niên ngày {DAY}/{MONTH}/{YEAR}',
                'description' => 'Xem lịch âm ngày {DAY} tháng {MONTH} năm {YEAR}, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt Nam. Lịch vạn niên ngày {DAY}/{MONTH}/{YEAR}.',
                'keywords' => 'lịch âm ngày {DAY} tháng {MONTH} năm {YEAR}, lịch vạn niên ngày {DAY}/{MONTH}/{YEAR}, giờ hoàng đạo ngày {DAY}, ngày tốt xấu ngày {DAY}, âm lịch ngày {DAY}',
                'is_active' => true,
            ],
        ];

        foreach ($seoSettings as $setting) {
            SeoSetting::updateOrCreate(
                ['page_key' => $setting['page_key']],
                $setting
            );
        }
    }
}