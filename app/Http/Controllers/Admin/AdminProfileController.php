<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminProfileController extends Controller
{
    /**
     * Show the profile edit page.
     */
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update profile details (Name, Email, Avatar Image).
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:admins,email,' . $admin->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'admin_' . $admin->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $destPath = public_path('uploads/admins');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }

            // Remove previous avatar if exists
            if ($admin->image && file_exists(public_path('uploads/' . $admin->image))) {
                @unlink(public_path('uploads/' . $admin->image));
            }

            $file->move($destPath, $filename);
            $admin->image = 'admins/' . $filename;
        }

        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update Admin Password.
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect!'])->withInput();
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Password changed successfully!');
    }
}
