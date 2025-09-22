<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'meta_tags',
        'gtag_code',
        'custom_scripts',
        'is_active',
    ];

    protected $casts = [
        'meta_tags' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get available meta tag locations
     */
    public static function getLocations()
    {
        return [
            'header' => 'Header',
            'footer' => 'Footer',
        ];
    }

    /**
     * Get meta tag by location name
     */
    public static function getByLocation($location)
    {
        return self::where('name', $location)->where('is_active', true)->first();
    }

    /**
     * Get all active meta tags
     */
    public static function getAllActive()
    {
        return self::where('is_active', true)->get()->keyBy('name');
    }

    /**
     * Get formatted meta tags for HTML output
     */
    public function getFormattedMetaTags()
    {
        if (!$this->meta_tags) {
            return '';
        }

        $html = '';
        foreach ($this->meta_tags as $tag) {
            if (isset($tag['name']) && isset($tag['content'])) {
                $html .= '<meta name="' . htmlspecialchars($tag['name']) . '" content="' . htmlspecialchars($tag['content']) . '">' . "\n";
            }
        }

        return $html;
    }

    /**
     * Get formatted gtag code
     */
    public function getFormattedGtagCode()
    {
        return $this->gtag_code ?: '';
    }

    /**
     * Scope a query to only include active meta tags.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted custom scripts
     */
    public function getFormattedCustomScripts()
    {
        return $this->custom_scripts ?: '';
    }
}