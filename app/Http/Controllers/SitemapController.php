<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\LunarCalendarService;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    protected $lunarCalendarService;

    public function __construct(LunarCalendarService $lunarCalendarService)
    {
        $this->lunarCalendarService = $lunarCalendarService;
    }

    /**
     * Generate sitemap index (sitemap of sitemaps)
     */
    public function sitemapIndex()
    {
        $cacheKey = 'sitemap_index_main_' . date('Y-m-d');
        
        $xml = Cache::remember($cacheKey, 24 * 60, function () {
            $currentYear = date('Y');
            $years = range($currentYear - 2, $currentYear + 10);
            
            $sitemaps = [];
            
            // Main sitemap
            $sitemaps[] = [
                'loc' => url('/sitemap.xml'),
                'lastmod' => now()->format('Y-m-d')
            ];
            
            // Year sitemaps
            foreach ($years as $year) {
                $sitemaps[] = [
                    'loc' => url("/sitemap/{$year}.xml"),
                    'lastmod' => now()->format('Y-m-d')
                ];
            }
            
            return $this->generateSitemapIndexXml($sitemaps);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }
    public function index()
    {
        $cacheKey = 'sitemap_index_' . date('Y-m-d');
        
        $xml = Cache::remember($cacheKey, 24 * 60, function () {
            $currentYear = date('Y');
            $years = range($currentYear - 2, $currentYear + 10); // 2 năm trước, 10 năm sau
            
            $sitemaps = [];
            
            // Main pages
            $sitemaps[] = [
                'loc' => url('/'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ];
            
            $sitemaps[] = [
                'loc' => url('/converter'),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ];
            
            // Year pages
            foreach ($years as $year) {
                $sitemaps[] = [
                    'loc' => url("/calendar/{$year}"),
                    'lastmod' => now()->format('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.7'
                ];
            }
            
            return $this->generateSitemapXml($sitemaps);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }

    /**
     * Generate year sitemap with months
     */
    public function year($year)
    {
        $cacheKey = "sitemap_year_{$year}_" . date('Y-m-d');
        
        $xml = Cache::remember($cacheKey, 7 * 24 * 60, function () use ($year) {
            $urls = [];
            
            // Year page
            $urls[] = [
                'loc' => url("/calendar/{$year}"),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ];
            
            // Month pages
            for ($month = 1; $month <= 12; $month++) {
                $urls[] = [
                    'loc' => url("/calendar/{$year}/{$month}"),
                    'lastmod' => now()->format('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority' => '0.6'
                ];
            }
            
            return $this->generateSitemapXml($urls);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }

    /**
     * Generate month sitemap with days
     */
    public function month($year, $month)
    {
        $cacheKey = "sitemap_month_{$year}_{$month}_" . date('Y-m-d');
        
        $xml = Cache::remember($cacheKey, 7 * 24 * 60, function () use ($year, $month) {
            $urls = [];
            
            // Month page
            $urls[] = [
                'loc' => url("/calendar/{$year}/{$month}"),
                'lastmod' => now()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.6'
            ];
            
            // Day pages
            $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $urls[] = [
                    'loc' => url("/calendar/{$year}/{$month}/{$day}"),
                    'lastmod' => now()->format('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.5'
                ];
            }
            
            return $this->generateSitemapXml($urls);
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }

    /**
     * Generate robots.txt
     */
    public function robots()
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Disallow: /storage/\n";
        $robots .= "Disallow: /vendor/\n";
        $robots .= "\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";
        $robots .= "Sitemap: " . url('/sitemap-years.xml') . "\n";

        return response($robots, 200, [
            'Content-Type' => 'text/plain; charset=utf-8'
        ]);
    }

    /**
     * Generate XML sitemap index
     */
    private function generateSitemapIndexXml($sitemaps)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($sitemaps as $sitemap) {
            $xml .= '  <sitemap>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($sitemap['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $sitemap['lastmod'] . '</lastmod>' . "\n";
            $xml .= '  </sitemap>' . "\n";
        }
        
        $xml .= '</sitemapindex>';
        
        return $xml;
    }

    /**
     * Generate XML sitemap
     */
    private function generateSitemapXml($urls)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
}
