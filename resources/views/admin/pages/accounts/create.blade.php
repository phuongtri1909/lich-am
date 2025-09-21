@extends('admin.layouts.sidebar')

@section('title', 'Thêm tài khoản mới')

@section('main-content')
<div class="category-form-container">
    <!-- Breadcrumb -->
    <div class="content-breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.accounts.index') }}">Quản lý tài khoản</a></li>
            <li class="breadcrumb-item current">Thêm mới</li>
        </ol>
    </div>

    <div class="form-card">
        <div class="form-header">
            <div class="form-title">
                <i class="fas fa-plus icon-title"></i>
                <h5>Thêm tài khoản mới</h5>
                <small class="text-muted">Tạo tài khoản mới cho hệ thống</small>
            </div>
        </div>
        <div class="form-body"> 
            <form action="{{ route('admin.accounts.store') }}" method="POST" class="category-form" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="full_name" class="form-label-custom">
                        Họ và tên <span class="required-mark">*</span>
                    </label>
                    <input type="text" class="custom-input {{ $errors->has('full_name') ? 'input-error' : '' }}" 
                        id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="255">
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
                        id="email" name="email" value="{{ old('email') }}" required maxlength="255">
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
                        Mật khẩu <span class="required-mark">*</span>
                    </label>
                    <input type="password" class="custom-input {{ $errors->has('password') ? 'input-error' : '' }}" 
                        id="password" name="password" required minlength="8">
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Mật khẩu phải có ít nhất 8 ký tự.</span>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label-custom">
                        Xác nhận mật khẩu <span class="required-mark">*</span>
                    </label>
                    <input type="password" class="custom-input {{ $errors->has('password_confirmation') ? 'input-error' : '' }}" 
                        id="password_confirmation" name="password_confirmation" required minlength="8">
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Nhập lại mật khẩu để xác nhận.</span>
                    </div>
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="avatar" class="form-label-custom">
                        Avatar
                    </label>
                    <input type="file" class="custom-input {{ $errors->has('avatar') ? 'input-error' : '' }}" 
                        id="avatar" name="avatar" accept="image/*">
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Chấp nhận định dạng: JPG, PNG, GIF. Tối đa 2MB.</span>
                    </div>
                    @error('avatar')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="active" name="active" value="1" 
                            {{ old('active', true) ? 'checked' : '' }}>
                        <label for="active">Kích hoạt tài khoản</label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('admin.accounts.index') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="save-button">
                        <i class="fas fa-save"></i> Tạo tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection