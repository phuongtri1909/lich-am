@extends('admin.layouts.sidebar')

@section('title', 'Tạo Meta Tags')

@section('main-content')
<div class="category-form-container">
    <!-- Breadcrumb -->
    <div class="content-breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.meta-tags.index') }}">Quản lý Meta Tags</a></li>
            <li class="breadcrumb-item current">Tạo mới</li>
        </ol>
    </div>

    <div class="form-card">
        <div class="form-header">
            <div class="form-title">
                <i class="fas fa-plus icon-title"></i>
                <h5>Tạo Meta Tags mới</h5>
            </div>
        </div>
        <div class="form-body">
            <form action="{{ route('admin.meta-tags.store') }}" method="POST" class="category-form">
                @csrf
                
                <!-- Location -->
                <div class="form-group">
                    <label for="name" class="form-label-custom">
                        Vị trí <span class="required-mark">*</span>
                    </label>
                    <select class="custom-input {{ $errors->has('name') ? 'input-error' : '' }}" 
                        id="name" name="name" required>
                        <option value="">Chọn vị trí</option>
                        @foreach($locations as $key => $label)
                            <option value="{{ $key }}" {{ old('name') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Chọn vị trí để đặt meta tags (header hoặc footer).</span>
                    </div>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Meta Tags -->
                <div class="form-group">
                    <label class="form-label-custom">Meta Tags</label>
                    <div id="meta-tags-container">
                        <div class="meta-tag-item">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="custom-input" name="meta_tags[0][name]" 
                                        placeholder="Tên meta tag (ví dụ: description)" value="{{ old('meta_tags.0.name') }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="custom-input" name="meta_tags[0][content]" 
                                        placeholder="Nội dung meta tag" value="{{ old('meta_tags.0.content') }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-meta-tag" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-meta-tag" class="btn btn-secondary btn-sm mt-2">
                        <i class="fas fa-plus"></i> Thêm Meta Tag
                    </button>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Thêm các meta tags như description, keywords, author, etc.</span>
                    </div>
                    @error('meta_tags.*')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- GTag Code -->
                <div class="form-group">
                    <label for="gtag_code" class="form-label-custom">
                        Google Analytics / GTM Code
                    </label>
                    <textarea class="custom-input {{ $errors->has('gtag_code') ? 'input-error' : '' }}" 
                        id="gtag_code" name="gtag_code" rows="4" placeholder="<!-- Google Analytics -->&#10;&lt;script&gt;&#10;  gtag('config', 'GA_MEASUREMENT_ID');&#10;&lt;/script&gt;">{{ old('gtag_code') }}</textarea>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Nhập code Google Analytics hoặc Google Tag Manager.</span>
                    </div>
                    @error('gtag_code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Custom Scripts -->
                <div class="form-group">
                    <label for="custom_scripts" class="form-label-custom">
                        Custom Scripts
                    </label>
                    <textarea class="custom-input {{ $errors->has('custom_scripts') ? 'input-error' : '' }}" 
                        id="custom_scripts" name="custom_scripts" rows="4" placeholder="<!-- Custom JavaScript -->&#10;&lt;script&gt;&#10;  // Your custom code here&#10;&lt;/script&gt;">{{ old('custom_scripts') }}</textarea>
                    <div class="form-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>Nhập các custom scripts khác (JavaScript, CSS, etc.).</span>
                    </div>
                    @error('custom_scripts')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active">Kích hoạt Meta Tags</label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="{{ route('admin.meta-tags.index') }}" class="back-button">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                   
                    <div class="action-group">
                        <button type="submit" class="save-button">
                            <i class="fas fa-save"></i> Tạo mới
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let metaTagIndex = 1;
    
    // Add meta tag
    document.getElementById('add-meta-tag').addEventListener('click', function() {
        const container = document.getElementById('meta-tags-container');
        const newItem = document.createElement('div');
        newItem.className = 'meta-tag-item';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-5">
                    <input type="text" class="custom-input" name="meta_tags[${metaTagIndex}][name]" 
                        placeholder="Tên meta tag">
                </div>
                <div class="col-md-6">
                    <input type="text" class="custom-input" name="meta_tags[${metaTagIndex}][content]" 
                        placeholder="Nội dung meta tag">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-meta-tag">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        metaTagIndex++;
        
        // Show remove buttons if more than 1 item
        updateRemoveButtons();
    });
    
    // Remove meta tag
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-meta-tag')) {
            e.target.closest('.meta-tag-item').remove();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.meta-tag-item');
        const removeButtons = document.querySelectorAll('.remove-meta-tag');
        
        removeButtons.forEach(button => {
            button.style.display = items.length > 1 ? 'block' : 'none';
        });
    }
    
    // Initialize
    updateRemoveButtons();
});
</script>
@endsection
