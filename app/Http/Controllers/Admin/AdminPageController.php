<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('sort_order')->orderBy('id', 'desc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:200',
            'slug'       => 'nullable|string|max:200|unique:pages,slug',
            'content'    => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);
        
        // Ensure unique slug
        $count = Page::where('slug', $slug)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        Page::create([
            'title'      => $request->title,
            'slug'       => $slug,
            'content'    => $request->content,
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->has('is_active'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:200',
            'slug'       => 'nullable|string|max:200|unique:pages,slug,' . $id,
            'content'    => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title);

        $page->update([
            'title'      => $request->title,
            'slug'       => $slug,
            'content'    => $request->content,
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => $request->has('is_active'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $page = Page::findOrFail($id);
        $page->is_active = !$page->is_active;
        $page->save();

        return redirect()->route('admin.pages.index')->with('success', 'Page status updated.');
    }
}
