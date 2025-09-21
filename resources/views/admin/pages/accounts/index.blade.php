@extends('admin.layouts.sidebar')

@section('title', 'Quản lý tài khoản')

@section('main-content')
<div class="category-container">
    <!-- Breadcrumb -->
    <div class="content-breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item current">Quản lý tài khoản</li>
        </ol>
    </div>

    <div class="content-card">
        <div class="card-top">
            <div class="card-title">
                <i class="fas fa-users icon-title"></i>
                <h5>Danh sách tài khoản</h5>
                <small class="text-muted">Quản lý tài khoản hệ thống</small>
            </div>
            <a href="{{ route('admin.accounts.create') }}" class="action-button">
                <i class="fas fa-plus"></i> Thêm tài khoản
            </a>
        </div>
        
        <div class="card-content">
            @if($users->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Chưa có tài khoản nào</h4>
                    <p>Bắt đầu bằng cách thêm tài khoản đầu tiên.</p>
                    <a href="{{ route('admin.accounts.create') }}" class="action-button">
                        <i class="fas fa-plus"></i> Thêm tài khoản mới
                    </a>
                </div>
            @else
                <div class="data-table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="column-small">STT</th>
                                <th class="column-medium">Avatar</th>
                                <th class="column-large">Thông tin</th>
                                <th class="column-medium">Email</th>
                                <th class="column-small">Trạng thái</th>
                                <th class="column-small text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                    <td class="text-center">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-thumbnail">
                                        @else
                                            <div class="avatar-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-name">{{ $user->full_name }}</div>
                                            <div class="user-role">
                                                @if($user->email === $adminEmail)
                                                    <span class="role-badge admin">Super Admin</span>
                                                @else
                                                    <span class="role-badge user">{{ ucfirst($user->role) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="email-cell">{{ $user->email }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if($user->isActive())
                                            <span class="status-badge active">Hoạt động</span>
                                        @else
                                            <span class="status-badge inactive">Tạm khóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons-wrapper">
                                            <a href="{{ route('admin.accounts.edit', $user) }}" class="action-icon edit-icon text-decoration-none" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->email !== $adminEmail && $user->id !== auth()->id())
                                                @include('components.delete-form', [
                                                    'id' => $user->id,
                                                    'route' => route('admin.accounts.destroy', $user),
                                                    'message' => "Bạn có chắc chắn muốn xóa tài khoản này?"
                                                ])
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Hiển thị {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }} của {{ $users->total() }} tài khoản
                    </div>
                    <div class="pagination-controls">
                        {{ $users->links('components.paginate') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-thumbnail {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
}

.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    border: 2px solid #ddd;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.user-name {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.user-role {
    display: flex;
    align-items: center;
}

.role-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.role-badge.admin {
    background: #dc3545;
    color: white;
}

.role-badge.user {
    background: #6c757d;
    color: white;
}

.email-cell {
    font-size: 13px;
    color: #666;
    word-break: break-all;
}
</style>
@endsection