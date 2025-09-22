<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeoController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seoSettings = SeoSetting::orderBy('page_key')->get();
        $pageKeys = SeoSetting::getPageKeys();
        
        return view('admin.pages.seo.index', compact('seoSettings', 'pageKeys'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeoSetting $seo)
    {
        $pageKeys = SeoSetting::getPageKeys();
        
        return view('admin.pages.seo.edit', compact('seo', 'pageKeys'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeoSetting $seo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'keywords' => 'required|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5012',
            'is_active' => 'boolean'
        ],[
            'title.required' => 'Title là bắt buộc.',
            'description.required' => 'Description là bắt buộc.',
            'keywords.required' => 'Keywords là bắt buộc.',
            'thumbnail.image' => 'Thumbnail phải là định dạng ảnh.',
            'thumbnail.mimes' => 'Thumbnail phải là định dạng ảnh.',
            'thumbnail.max' => 'Thumbnail phải có dung lượng nhỏ hơn 5MB.',
            'is_active.boolean' => 'Trạng thái phải là boolean.',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Upload new thumbnail if provided
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($seo->thumbnail) {
                Storage::disk('public')->delete($seo->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('seo-thumbnails', 'public');
        }

        $seo->update($data);

        return redirect()->route('admin.seo.index')
            ->with('success', 'SEO settings đã được cập nhật thành công.');
    }
}
