<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class AdminBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(20);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'sort_order' => 'nullable|integer',
        ]);

        Banner::create([
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->has('is_active'),
            'image'      => $this->uploadImage($request->file('image')),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner added.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'sort_order' => 'nullable|integer',
        ]);

        $data = [
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Delete old image, then store the new one
            $this->deleteImage($banner->image);
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $this->deleteImage($banner->image);
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted.');
    }

    /**
     * Store banner image under public/uploads/banners and return relative path.
     */
    private function uploadImage($file): string
    {
        $dir = public_path('uploads/banners');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($dir, $filename);
        return 'banners/' . $filename;
    }

    /**
     * Delete a banner image file if it exists.
     */
    private function deleteImage(?string $path): void
    {
        if ($path && file_exists(public_path('uploads/' . $path))) {
            unlink(public_path('uploads/' . $path));
        }
    }
}
