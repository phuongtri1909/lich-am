<?php

namespace Database\Seeders;

use App\Models\MetaTag;
use Illuminate\Database\Seeder;

class MetaTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Header Meta Tags
        MetaTag::create([
            'name' => 'header',
            'meta_tags' => [
                [
                    'name' => 'description',
                    'content' => 'Lịch âm Việt Nam - Xem ngày âm lịch, chọn giờ chuẩn xác. Tra cứu lịch âm, đổi ngày âm dương, xem ngày tốt xấu.'
                ],
                [
                    'name' => 'keywords',
                    'content' => 'lịch âm, âm lịch việt nam, xem ngày âm lịch, đổi ngày âm dương, ngày tốt xấu, chọn giờ'
                ],
                [
                    'name' => 'author',
                    'content' => 'Lịch Âm Việt Nam'
                ],
                [
                    'name' => 'robots',
                    'content' => 'index, follow'
                ],
                [
                    'name' => 'viewport',
                    'content' => 'width=device-width, initial-scale=1.0'
                ]
            ],
            'gtag_code' => '<!-- Google Analytics -->' . "\n" . 
                '<script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>' . "\n" .
                '<script>' . "\n" .
                '  window.dataLayer = window.dataLayer || [];' . "\n" .
                '  function gtag(){dataLayer.push(arguments);}' . "\n" .
                '  gtag(\'js\', new Date());' . "\n" .
                '  gtag(\'config\', \'GA_MEASUREMENT_ID\');' . "\n" .
                '</script>',
            'custom_scripts' => '<!-- Custom Header Scripts -->' . "\n" .
                '<script>' . "\n" .
                '  // Add your custom header scripts here' . "\n" .
                '</script>',
            'is_active' => true,
        ]);

        // Footer Meta Tags
        MetaTag::create([
            'name' => 'footer',
            'meta_tags' => [],
            'gtag_code' => '',
            'custom_scripts' => '<!-- Custom Footer Scripts -->' . "\n" .
                '<script>' . "\n" .
                '  // Add your custom footer scripts here' . "\n" .
                '  console.log(\'Footer scripts loaded\');' . "\n" .
                '</script>',
            'is_active' => true,
        ]);
    }
}