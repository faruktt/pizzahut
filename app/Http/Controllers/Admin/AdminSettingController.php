<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $logo = Setting::getValue('site_logo');
        $favicon = Setting::getValue('site_favicon');
        return view('admin.settings.index', compact('logo', 'favicon'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $this->deleteFile(Setting::getValue('site_logo'));
        Setting::setValue('site_logo', $this->upload($request->file('logo')));

        return redirect()->route('admin.settings.index')->with('success', 'Website logo updated.');
    }

    public function updateFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|file|mimes:ico,png,jpg,jpeg,svg,webp|max:1024',
        ]);

        $this->deleteFile(Setting::getValue('site_favicon'));
        Setting::setValue('site_favicon', $this->upload($request->file('favicon')));

        return redirect()->route('admin.settings.index')->with('success', 'Favicon updated.');
    }

    public function removeLogo()
    {
        $this->deleteFile(Setting::getValue('site_logo'));
        Setting::removeValue('site_logo');
        return redirect()->route('admin.settings.index')->with('success', 'Logo removed.');
    }

    public function removeFavicon()
    {
        $this->deleteFile(Setting::getValue('site_favicon'));
        Setting::removeValue('site_favicon');
        return redirect()->route('admin.settings.index')->with('success', 'Favicon removed.');
    }

    /**
     * Store branding files under public/uploads/brand and return relative path.
     */
    private function upload($file): string
    {
        $dir = public_path('uploads/brand');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($dir, $filename);
        return 'brand/' . $filename;
    }

    /**
     * Delete a branding file if it exists.
     */
    private function deleteFile(?string $path): void
    {
        if ($path && file_exists(public_path('uploads/' . $path))) {
            unlink(public_path('uploads/' . $path));
        }
    }
}
