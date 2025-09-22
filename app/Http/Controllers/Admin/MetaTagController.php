<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetaTag;
use Illuminate\Http\Request;

class MetaTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metaTags = MetaTag::orderBy('name')->get();
        $locations = MetaTag::getLocations();
        
        return view('admin.pages.meta-tags.index', compact('metaTags', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = MetaTag::getLocations();
        return view('admin.pages.meta-tags.create', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:meta_tags,name',
            'meta_tags' => 'nullable|array',
            'meta_tags.*.name' => 'required_with:meta_tags|string|max:255',
            'meta_tags.*.content' => 'required_with:meta_tags|string|max:500',
            'gtag_code' => 'nullable|string',
            'custom_scripts' => 'nullable|string',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Tên vị trí là bắt buộc.',
            'name.unique' => 'Tên vị trí đã tồn tại.',
            'meta_tags.*.name.required_with' => 'Tên meta tag là bắt buộc.',
            'meta_tags.*.content.required_with' => 'Nội dung meta tag là bắt buộc.',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        MetaTag::create($data);

        return redirect()->route('admin.meta-tags.index')
            ->with('success', 'Meta tags đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MetaTag $metaTag)
    {
        $locations = MetaTag::getLocations();
        return view('admin.pages.meta-tags.edit', compact('metaTag', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MetaTag $metaTag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:meta_tags,name,' . $metaTag->id,
            'meta_tags' => 'nullable|array',
            'meta_tags.*.name' => 'required_with:meta_tags|string|max:255',
            'meta_tags.*.content' => 'required_with:meta_tags|string|max:500',
            'gtag_code' => 'nullable|string',
            'custom_scripts' => 'nullable|string',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Tên vị trí là bắt buộc.',
            'name.unique' => 'Tên vị trí đã tồn tại.',
            'meta_tags.*.name.required_with' => 'Tên meta tag là bắt buộc.',
            'meta_tags.*.content.required_with' => 'Nội dung meta tag là bắt buộc.',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $metaTag->update($data);

        return redirect()->route('admin.meta-tags.index')
            ->with('success', 'Meta tags đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MetaTag $metaTag)
    {
        $metaTag->delete();

        return redirect()->route('admin.meta-tags.index')
            ->with('success', 'Meta tags đã được xóa thành công.');
    }
}