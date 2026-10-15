@extends('layouts.admin')

@section('page-title', 'Admin Profile Settings')

@push('admin-styles')
<style>
    .profile-grid-container {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 24px;
        align-items: start;
    }

    .profile-card {
        background: #ffffff;
        border: 1px solid var(--border-light, #e2e8f0);
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }

    .profile-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .profile-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #fee2e2;
        color: var(--admin-primary, #c8102e);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .profile-card-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
    }

    .profile-card-desc {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 2px;
    }

    /* Avatar Upload Preview Box */
    .avatar-upload-box {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        padding: 16px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
    }

    .avatar-preview-wrapper {
        position: relative;
        width: 86px;
        height: 86px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        background: #e2e8f0;
        flex-shrink: 0;
    }

    .avatar-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-fallback-icon {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: #f8fafc;
        font-size: 2.2rem;
    }

    .avatar-upload-actions {
        flex: 1;
    }

    .avatar-upload-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .avatar-upload-label:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .avatar-upload-hint {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 6px;
        line-height: 1.4;
    }

    /* Password Input with eye */
    .input-with-eye {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-eye .form-control {
        padding-right: 42px;
    }

    .eye-toggle-btn {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.95rem;
        padding: 4px;
    }
    .eye-toggle-btn:hover {
        color: #475569;
    }

    @media (max-width: 900px) {
        .profile-grid-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('admin-content')
<div class="profile-grid-container">

    <!-- 1. EDIT PROFILE INFO & AVATAR -->
    <div class="profile-card">
        <div class="profile-card-header">
            <div class="profile-card-icon">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div>
                <div class="profile-card-title">Profile Information</div>
                <div class="profile-card-desc">Update your administrator name, email address, and profile photo</div>
            </div>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Preview & Upload -->
            <div class="avatar-upload-box">
                <div class="avatar-preview-wrapper" id="avatarPreviewContainer">
                    @if($admin->image && file_exists(public_path('uploads/' . $admin->image)))
                        <img src="{{ asset('uploads/' . $admin->image) }}" alt="{{ $admin->name }}" class="avatar-preview-img" id="avatarPreviewImg">
                    @else
                        <div class="avatar-fallback-icon" id="avatarFallback">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <img src="" alt="Preview" class="avatar-preview-img" id="avatarPreviewImg" style="display: none;">
                    @endif
                </div>
                <div class="avatar-upload-actions">
                    <label class="avatar-upload-label" for="adminAvatarInput">
                        <i class="fa-solid fa-camera"></i>
                        <span>Upload New Photo</span>
                    </label>
                    <input 
                        type="file" 
                        name="image" 
                        id="adminAvatarInput" 
                        accept="image/*" 
                        style="display: none;" 
                        onchange="previewAdminAvatar(event)"
                    >
                    <div class="avatar-upload-hint">
                        Recommended: Square JPG, PNG, or WebP. Max 3MB.
                    </div>
                </div>
            </div>

            <!-- Name -->
            <div class="form-group">
                <label class="form-label" for="adminName">Full Name *</label>
                <input 
                    type="text" 
                    name="name" 
                    id="adminName" 
                    class="form-control" 
                    value="{{ old('name', $admin->name) }}" 
                    required
                >
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label" for="adminEmail">Email Address *</label>
                <input 
                    type="email" 
                    name="email" 
                    id="adminEmail" 
                    class="form-control" 
                    value="{{ old('email', $admin->email) }}" 
                    required
                >
            </div>

            <!-- Role (Read-only badge) -->
            <div class="form-group">
                <label class="form-label">Role & Privileges</label>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="badge-active" style="padding:6px 12px;font-size:0.8rem;border-radius:6px;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-shield-halved"></i> {{ ucfirst($admin->role ?? 'admin') }} (Super Administrator)
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding:10px 20px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Profile Changes
            </button>
        </form>
    </div>

    <!-- 2. CHANGE PASSWORD -->
    <div class="profile-card" id="passwordSection">
        <div class="profile-card-header">
            <div class="profile-card-icon" style="background:#fef3c7; color:#d97706;">
                <i class="fa-solid fa-key"></i>
            </div>
            <div>
                <div class="profile-card-title">Change Password</div>
                <div class="profile-card-desc">Keep your admin account secure with a strong password</div>
            </div>
        </div>

        <form action="{{ route('admin.profile.password') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div class="form-group">
                <label class="form-label" for="current_password">Current Password *</label>
                <div class="input-with-eye">
                    <input 
                        type="password" 
                        name="current_password" 
                        id="current_password" 
                        class="form-control" 
                        placeholder="Enter current password" 
                        required
                    >
                    <button type="button" class="eye-toggle-btn" onclick="togglePasswordVisibility('current_password', 'currentEye')">
                        <i class="fa-regular fa-eye" id="currentEye"></i>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label class="form-label" for="password">New Password *</label>
                <div class="input-with-eye">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control" 
                        placeholder="Minimum 6 characters" 
                        required
                    >
                    <button type="button" class="eye-toggle-btn" onclick="togglePasswordVisibility('password', 'newEye')">
                        <i class="fa-regular fa-eye" id="newEye"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm New Password *</label>
                <div class="input-with-eye">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="form-control" 
                        placeholder="Re-type new password" 
                        required
                    >
                    <button type="button" class="eye-toggle-btn" onclick="togglePasswordVisibility('password_confirmation', 'confirmEye')">
                        <i class="fa-regular fa-eye" id="confirmEye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="padding:10px 20px; background:#d97706;">
                <i class="fa-solid fa-lock"></i> Update Password
            </button>
        </form>
    </div>

</div>
@endsection

@push('admin-scripts')
<script>
    function previewAdminAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgEl = document.getElementById('avatarPreviewImg');
                const fallbackEl = document.getElementById('avatarFallback');

                imgEl.src = e.target.result;
                imgEl.style.display = 'block';
                if (fallbackEl) fallbackEl.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    }

    function togglePasswordVisibility(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        if (input && eye) {
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }
    }
</script>
@endpush
