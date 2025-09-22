<?php

namespace App\Services;

use App\Models\SeoSetting;
use Artesaos\SEOTools\Facades\SEOTools;

class SeoService
{
    /**
     * Extract text from SEO field (handle both array and string)
     */
    private function extractSeoText($field)
    {
        if (is_array($field)) {
            return $field['vi'] ?? $field[0] ?? '';
        }
        return $field ?? '';
    }
    /**
     * Set SEO for home page
     */
    public function setHomeSeo()
    {
        $seo = SeoSetting::getByPageKey('home');
        
        if ($seo) {
            $title = $this->extractSeoText($seo->title);
            $description = $this->extractSeoText($seo->description);
            $keywords = $this->extractSeoText($seo->keywords);
            
            SEOTools::setTitle($title);
            SEOTools::setDescription($description);
            SEOTools::metatags()->setKeywords($keywords);
            
            // Open Graph
            SEOTools::opengraph()->setTitle($title);
            SEOTools::opengraph()->setDescription($description);
            SEOTools::opengraph()->setUrl(request()->url());
            SEOTools::opengraph()->setType('website');
            
            // Twitter Card
            SEOTools::twitter()->setTitle($title);
            SEOTools::twitter()->setDescription($description);
            SEOTools::twitter()->setSite('@lichamvn');
        }
    }

    /**
     * Set SEO for converter page
     */
    public function setConverterSeo()
    {
        $seo = SeoSetting::getByPageKey('converter');
        
        if ($seo) {
            $title = $this->extractSeoText($seo->title);
            $description = $this->extractSeoText($seo->description);
            $keywords = $this->extractSeoText($seo->keywords);
            
            SEOTools::setTitle($title);
            SEOTools::setDescription($description);
            SEOTools::metatags()->setKeywords($keywords);
            
            // Open Graph
            SEOTools::opengraph()->setTitle($title);
            SEOTools::opengraph()->setDescription($description);
            SEOTools::opengraph()->setUrl(request()->url());
            SEOTools::opengraph()->setType('website');
            
            // Twitter Card
            SEOTools::twitter()->setTitle($title);
            SEOTools::twitter()->setDescription($description);
            SEOTools::twitter()->setSite('@lichamvn');
        }
    }

    /**
     * Set SEO for year page
     */
    public function setYearSeo($year)
    {
        $seo = SeoSetting::getByPageKey('calendar_year');
        
        if ($seo) {
            $title = $this->replaceVariables($this->extractSeoText($seo->title), ['YEAR' => $year]);
            $description = $this->replaceVariables($this->extractSeoText($seo->description), ['YEAR' => $year]);
            $keywords = $this->replaceVariables($this->extractSeoText($seo->keywords), ['YEAR' => $year]);
            
            SEOTools::setTitle($title);
            SEOTools::setDescription($description);
            SEOTools::metatags()->setKeywords($keywords);
            
            // Open Graph
            SEOTools::opengraph()->setTitle($title);
            SEOTools::opengraph()->setDescription($description);
            SEOTools::opengraph()->setUrl(request()->url());
            SEOTools::opengraph()->setType('website');
            
            // Twitter Card
            SEOTools::twitter()->setTitle($title);
            SEOTools::twitter()->setDescription($description);
            SEOTools::twitter()->setSite('@lichamvn');
        }
    }

    /**
     * Set SEO for month page
     */
    public function setMonthSeo($year, $month)
    {
        $seo = SeoSetting::getByPageKey('calendar_month');
        
        if ($seo) {
            $monthName = $this->getMonthName($month);
            $title = $this->replaceVariables($this->extractSeoText($seo->title), [
                'YEAR' => $year,
                'MONTH' => $month,
                'MONTH_NAME' => $monthName
            ]);
            $description = $this->replaceVariables($this->extractSeoText($seo->description), [
                'YEAR' => $year,
                'MONTH' => $month,
                'MONTH_NAME' => $monthName
            ]);
            $keywords = $this->replaceVariables($this->extractSeoText($seo->keywords), [
                'YEAR' => $year,
                'MONTH' => $month,
                'MONTH_NAME' => $monthName
            ]);
            
            SEOTools::setTitle($title);
            SEOTools::setDescription($description);
            SEOTools::metatags()->setKeywords($keywords);
            
            // Open Graph
            SEOTools::opengraph()->setTitle($title);
            SEOTools::opengraph()->setDescription($description);
            SEOTools::opengraph()->setUrl(request()->url());
            SEOTools::opengraph()->setType('website');
            
            // Twitter Card
            SEOTools::twitter()->setTitle($title);
            SEOTools::twitter()->setDescription($description);
            SEOTools::twitter()->setSite('@lichamvn');
        }
    }

    /**
     * Set SEO for day page
     */
    public function setDaySeo($year, $month, $day)
    {
        $seo = SeoSetting::getByPageKey('calendar_day');
        
        if ($seo) {
            $monthName = $this->getMonthName($month);
            $dayName = $this->getDayName($year, $month, $day);
            
            $title = $this->replaceVariables($this->extractSeoText($seo->title), [
                'YEAR' => $year,
                'MONTH' => $month,
                'DAY' => $day,
                'MONTH_NAME' => $monthName,
                'DAY_NAME' => $dayName
            ]);
            $description = $this->replaceVariables($this->extractSeoText($seo->description), [
                'YEAR' => $year,
                'MONTH' => $month,
                'DAY' => $day,
                'MONTH_NAME' => $monthName,
                'DAY_NAME' => $dayName
            ]);
            $keywords = $this->replaceVariables($this->extractSeoText($seo->keywords), [
                'YEAR' => $year,
                'MONTH' => $month,
                'DAY' => $day,
                'MONTH_NAME' => $monthName,
                'DAY_NAME' => $dayName
            ]);
            
            SEOTools::setTitle($title);
            SEOTools::setDescription($description);
            SEOTools::metatags()->setKeywords($keywords);
            
            // Open Graph
            SEOTools::opengraph()->setTitle($title);
            SEOTools::opengraph()->setDescription($description);
            SEOTools::opengraph()->setUrl(request()->url());
            SEOTools::opengraph()->setType('website');
            
            // Twitter Card
            SEOTools::twitter()->setTitle($title);
            SEOTools::twitter()->setDescription($description);
            SEOTools::twitter()->setSite('@lichamvn');
        }
    }

    /**
     * Replace variables in SEO content
     */
    private function replaceVariables($content, $variables)
    {
        foreach ($variables as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }
        return $content;
    }

    /**
     * Get Vietnamese month name
     */
    private function getMonthName($month)
    {
        $months = [
            1 => 'tháng 1', 2 => 'tháng 2', 3 => 'tháng 3', 4 => 'tháng 4',
            5 => 'tháng 5', 6 => 'tháng 6', 7 => 'tháng 7', 8 => 'tháng 8',
            9 => 'tháng 9', 10 => 'tháng 10', 11 => 'tháng 11', 12 => 'tháng 12'
        ];
        
        return $months[$month] ?? "tháng {$month}";
    }

    /**
     * Get Vietnamese day name
     */
    private function getDayName($year, $month, $day)
    {
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        $dayOfWeek = date('w', $timestamp);
        
        $days = [
            0 => 'Chủ Nhật', 1 => 'Thứ Hai', 2 => 'Thứ Ba', 3 => 'Thứ Tư',
            4 => 'Thứ Năm', 5 => 'Thứ Sáu', 6 => 'Thứ Bảy'
        ];
        
        return $days[$dayOfWeek] ?? '';
    }
}
