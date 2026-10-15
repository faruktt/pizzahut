<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    /**
     * List all staff / admins
     */
    public function index()
    {
        $admins = Admin::with('roleModel')->latest()->paginate(15);
        $roles = Role::all();

        return view('admin.staff.index', compact('admins', 'roles'));
    }

    /**
     * Show create staff form
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.staff.create', compact('roles'));
    }

    /**
     * Store new staff
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'admin_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/admins'), $imageName);
            $imageName = 'admins/' . $imageName;
        }

        $role = Role::find($request->role_id);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
            'role' => $role->slug === 'super-admin' ? 'super_admin' : 'staff',
            'image' => $imageName,
        ]);

        return redirect()->route('admin.staff.index')->with('success', "নতুন স্টাফ মেম্বার '{$request->name}' সফলভাবে তৈরি করা হয়েছে।");
    }

    /**
     * Show edit staff form
     */
    public function edit($id)
    {
        $staff = Admin::findOrFail($id);
        $roles = Role::all();

        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    /**
     * Update staff member
     */
    public function update(Request $request, $id)
    {
        $staff = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email,' . $staff->id,
            'password' => 'nullable|min:6',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $staff->name = $request->name;
        $staff->email = $request->email;

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $role = Role::find($request->role_id);
        $staff->role_id = $role->id;
        $staff->role = $role->slug === 'super-admin' ? 'super_admin' : 'staff';

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($staff->image && file_exists(public_path('uploads/' . $staff->image))) {
                @unlink(public_path('uploads/' . $staff->image));
            }

            $image = $request->file('image');
            $imageName = 'admin_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/admins'), $imageName);
            $staff->image = 'admins/' . $imageName;
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', "স্টাফ মেম্বার '{$staff->name}' এর তথ্য আপডেট করা হয়েছে।");
    }

    /**
     * Delete staff member
     */
    public function destroy($id)
    {
        $currentAdmin = Auth::guard('admin')->user();

        if ($currentAdmin->id == $id) {
            return redirect()->back()->with('error', 'আপনি নিজের অ্যাকাউন্ট নিজে মুছে ফেলতে পারবেন না।');
        }

        $staff = Admin::findOrFail($id);
        $name = $staff->name;

        if ($staff->image && file_exists(public_path('uploads/' . $staff->image))) {
            @unlink(public_path('uploads/' . $staff->image));
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', "স্টাফ মেম্বার '{$name}' মুছে ফেলা হয়েছে।");
    }
}
