<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\SitemapController;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--clear : Clear cache before generating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap and warm up cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');
        
        if ($this->option('clear')) {
            $this->info('Clearing sitemap cache...');
            Cache::forget('sitemap_index_' . date('Y-m-d'));
            
            // Clear year caches
            $currentYear = date('Y');
            $years = range($currentYear - 2, $currentYear + 10);
            foreach ($years as $year) {
                Cache::forget("sitemap_year_{$year}_" . date('Y-m-d'));
                
                // Clear month caches
                for ($month = 1; $month <= 12; $month++) {
                    Cache::forget("sitemap_month_{$year}_{$month}_" . date('Y-m-d'));
                }
            }
        }
        
        $sitemapController = app(SitemapController::class);
        
        // Generate main sitemap
        $this->info('Generating main sitemap...');
        $response = $sitemapController->index();
        $this->info('✅ Main sitemap generated (' . strlen($response->getContent()) . ' chars)');
        
        // Generate year sitemaps
        $currentYear = date('Y');
        $years = range($currentYear - 2, $currentYear + 10);
        
        foreach ($years as $year) {
            $this->info("Generating year sitemap for {$year}...");
            $response = $sitemapController->year($year);
            $this->info("✅ Year sitemap {$year} generated (" . strlen($response->getContent()) . ' chars)');
        }
        
        // Generate month sitemaps for current year
        $this->info("Generating month sitemaps for {$currentYear}...");
        for ($month = 1; $month <= 12; $month++) {
            $response = $sitemapController->month($currentYear, $month);
            $this->info("✅ Month sitemap {$currentYear}-{$month} generated (" . strlen($response->getContent()) . ' chars)');
        }
        
        $this->info('🎉 Sitemap generation completed successfully!');
        $this->info('Sitemap URLs:');
        $this->line('- Main: ' . url('/sitemap.xml'));
        $this->line('- Years: ' . url('/sitemap-years.xml'));
        $this->line('- Robots: ' . url('/robots.txt'));
    }
}