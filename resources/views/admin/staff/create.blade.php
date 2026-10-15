@extends('layouts.admin')

@section('title', 'Add Staff Member')
@section('page-title', 'Add Staff Member')

@section('admin-content')
<div style="max-width:680px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">
    <!-- Back Link -->
    <a href="{{ route('admin.staff.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:#64748b;text-decoration:none;font-size:0.85rem;font-weight:600;">
        <i class="fa-solid fa-arrow-left"></i> Back to Staff
    </a>

    <form method="POST" action="{{ route('admin.staff.store') }}" enctype="multipart/form-data" style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:26px;display:flex;flex-direction:column;gap:18px;">
        @csrf

        <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;">Full Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Staff full name" required style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.88rem;font-family:'Poppins',sans-serif;outline:none;">
            @error('name')
                <div style="color:#ef4444;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;">Email Address *</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="staff@italianpizzahut.com" required style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.88rem;font-family:'Poppins',sans-serif;outline:none;">
            @error('email')
                <div style="color:#ef4444;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;">Password *</label>
                <input type="password" name="password" placeholder="At least 6 characters" required style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.88rem;font-family:'Poppins',sans-serif;outline:none;">
                @error('password')
                    <div style="color:#ef4444;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;">Assign Role *</label>
                <select name="role_id" required style="width:100%;padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.88rem;font-family:'Poppins',sans-serif;outline:none;background:#fff;">
                    <option value="">Select a Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div style="color:#ef4444;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:6px;">Profile Photo</label>
            <input type="file" name="image" accept="image/*" style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:8px;font-size:0.85rem;background:#f8fafc;">
            @error('image')
                <div style="color:#ef4444;font-size:0.75rem;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Form Submit -->
        <div style="border-top:1px solid #f1f5f9;padding-top:16px;display:flex;align-items:center;justify-content:flex-end;gap:12px;">
            <a href="{{ route('admin.staff.index') }}" style="padding:9px 18px;background:#f1f5f9;color:#64748b;border-radius:8px;font-size:0.85rem;font-weight:600;text-decoration:none;">Cancel</a>
            <button type="submit" style="padding:9px 22px;background:var(--admin-primary);color:#fff;border:none;border-radius:8px;font-size:0.85rem;font-weight:700;cursor:pointer;">Save Staff</button>
        </div>
    </form>
</div>
@endsection
