@extends('admin.layouts.sidebar')

@section('title', 'Chỉnh sửa tài khoản')

@section('main-content')
<div class="category-form-container">
    <!-- Breadcrumb -->
    <div class="content-breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}">Quản lý tài khoản</a></li>
            <li class="breadcrumb-item current">Chỉnh sửa</li>
        </ol>
    </div>

    <div class="form-card">
        <div class="form-header">
            <div class="form-title">
                <i class="fas fa-edit icon-title"></i>
                <h5>Chỉnh sửa tài khoản</h5>
                <small class="text-muted">Cập nhật thông tin tài khoản</small>
            </div>
            <div class="category-meta">
                <div class="category-created">
                    <i class="fas fa-clock"></i>
                    <span>Ngày tạo: {{ $account->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($account->email === $adminEmail)
                    <div class="admin-badge">
                        <i class="fas fa-crown"></i>
                        <span>Super Admin</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="form-body">
            
            <form action="{{ route('admin.accounts.update', $account) }}" method="POST" class="category-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="full_name" class="form-label-custom">
                        Họ và tên <span class="required-mark">*</span>
                    </label>
                    <input type="text" class="custom-input {{ $errors->has('full_name') ? 'input-error' : '' }}" 
                        id="full_name" name="full_name" value="{{ old('full_name', $account->full_name) }}" required maxlength="255">
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Nhập họ và tên đầy đủ của người dùng.</span>
                    </div>
                    @error('full_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label-custom">
                        Email <span class="required-mark">*</span>
                    </label>
                    <input type="email" class="custom-input {{ $errors->has('email') ? 'input-error' : '' }}" 
                        id="email" name="email" value="{{ old('email', $account->email) }}" required maxlength="255">
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Email sẽ được sử dụng để đăng nhập hệ thống.</span>
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label-custom">
                        Mật khẩu mới
                    </label>
                    <input type="password" class="custom-input {{ $errors->has('password') ? 'input-error' : '' }}" 
                        id="password" name="password" minlength="8">
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Để trống nếu không muốn thay đổi mật khẩu. Mật khẩu phải có ít nhất 8 ký tự.</span>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label-custom">
                        Xác nhận mật khẩu mới
                    </label>
                    <input type="password" class="custom-input {{ $errors->has('password_confirmation') ? 'input-error' : '' }}" 
                        id="password_confirmation" name="password_confirmation" minlength="8">
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Nhập lại mật khẩu mới để xác nhận.</span>
                    </div>
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="avatar" class="form-label-custom">
                        Avatar
                    </label>
                    @if($account->avatar)
                        <div class="current-avatar mb-3">
                            <img src="{{ asset('storage/' . $account->avatar) }}" alt="Avatar hiện tại" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
                        </div>
                    @endif
                    <input type="file" class="custom-input {{ $errors->has('avatar') ? 'input-error' : '' }}" 
                        id="avatar" name="avatar" accept="image/*">
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Để trống nếu không muốn thay đổi avatar. Chấp nhận định dạng: JPG, PNG, GIF. Tối đa 2MB.</span>
                    </div>
                    @error('avatar')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="active" name="active" value="1" 
                            {{ old('active', $account->isActive()) ? 'checked' : '' }}>
                        <label for="active">Kích hoạt tài khoản</label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('admin.accounts.index') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                   
                    <div class="action-group">
                       
                        <button type="submit" class="save-button">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.admin-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    background-color: #dc3545;
    color: white;
    margin-left: 10px;
}

.admin-badge i {
    margin-right: 5px;
}

.current-avatar {
    text-align: center;
}
</style>
@endsection