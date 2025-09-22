@extends('admin.layouts.sidebar')

@section('title', 'Quản lý Meta Tags')

@section('main-content')
<div class="category-container">
    <!-- Breadcrumb -->
    <div class="content-breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item current">Quản lý Meta Tags</li>
        </ol>
    </div>

    <div class="content-card">
        <div class="card-top">
            <div class="card-title">
                <i class="fas fa-code icon-title"></i>
                <h5>Danh sách Meta Tags</h5>
                <small class="text-muted">Quản lý meta tags và scripts cho header/footer</small>
            </div>
            <div class="card-actions">
                <a href="{{ route('admin.meta-tags.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm mới
                </a>
            </div>
        </div>
        
        <div class="card-content">
            @if($metaTags->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h4>Chưa có Meta Tags nào</h4>
                    <p>Hãy tạo meta tags cho header và footer.</p>
                </div>
            @else
                <div class="data-table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="column-small">STT</th>
                                <th class="column-medium">Vị trí</th>
                                <th class="column-large">Meta Tags</th>
                                <th class="column-medium">GTag Code</th>
                                <th class="column-small">Trạng thái</th>
                                <th class="column-small text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($metaTags as $index => $metaTag)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="location-info">
                                            <div class="location-name">{{ $locations[$metaTag->name] ?? $metaTag->name }}</div>
                                            <div class="location-key text-muted">{{ $metaTag->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="meta-tags-preview">
                                            @if($metaTag->meta_tags && count($metaTag->meta_tags) > 0)
                                                @foreach(array_slice($metaTag->meta_tags, 0, 2) as $tag)
                                                    <div class="meta-tag-item">
                                                        <span class="tag-name">{{ $tag['name'] }}:</span>
                                                        <span class="tag-content">{{ Str::limit($tag['content'], 50) }}</span>
                                                    </div>
                                                @endforeach
                                                @if(count($metaTag->meta_tags) > 2)
                                                    <div class="meta-tag-more">+{{ count($metaTag->meta_tags) - 2 }} tags khác</div>
                                                @endif
                                            @else
                                                <div class="no-meta-tags">Chưa có meta tags</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($metaTag->gtag_code)
                                            <div class="gtag-preview">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <span>Có GTag</span>
                                            </div>
                                        @else
                                            <div class="no-gtag">
                                                <i class="fas fa-times-circle text-muted"></i>
                                                <span>Chưa có</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($metaTag->is_active)
                                            <span class="status-badge active">Hoạt động</span>
                                        @else
                                            <span class="status-badge inactive">Tạm khóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons-wrapper">
                                            <a href="{{ route('admin.meta-tags.edit', $metaTag) }}" class="action-icon edit-icon text-decoration-none" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.meta-tags.destroy', $metaTag) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-icon delete-icon" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.location-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.location-name {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.location-key {
    font-size: 12px;
    color: #6c757d;
}

.meta-tags-preview {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.meta-tag-item {
    font-size: 12px;
    line-height: 1.3;
}

.tag-name {
    font-weight: 500;
    color: #333;
}

.tag-content {
    color: #6c757d;
}

.meta-tag-more {
    font-size: 11px;
    color: #6c757d;
    font-style: italic;
}

.no-meta-tags {
    font-size: 12px;
    color: #6c757d;
    font-style: italic;
}

.gtag-preview, .no-gtag {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
}

.gtag-preview span {
    color: #28a745;
}

.no-gtag span {
    color: #6c757d;
}
</style>
@endsection
