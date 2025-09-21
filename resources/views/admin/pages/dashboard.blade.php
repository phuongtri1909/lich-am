@extends('admin.layouts.sidebar')

@section('title', 'Dashboard')

@section('main-content')
    <div class="category-container">
        <!-- Breadcrumb -->
        <div class="content-breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item current">Dashboard</li>
            </ol>
        </div>

        <!-- Stats Cards -->
        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-chart-line icon-title"></i>
                    <h5>Thống kê tổng quan</h5>
                </div>
            </div>
            <div class="card-content">
                <div class="stats-grid">
                    <!-- Page Views -->
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($pageViewStats['total_views']) }}</h3>
                            <p>Tổng lượt xem (30 ngày)</p>
                        </div>
                    </div>

                    <!-- Unique Visitors -->
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($pageViewStats['unique_visitors']) }}</h3>
                            <p>Lượt truy cập duy nhất</p>
                        </div>
                    </div>

                    <!-- Project Views -->
                    <div class="stat-card highlight">
                        <div class="stat-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($projectViews) }}</h3>
                            <p>Lượt xem dự án (30 ngày)</p>
                        </div>
                    </div>

                    <!-- Contact Submissions -->
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($contactStats['total']) }}</h3>
                            <p>Tổng liên hệ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-chart-bar icon-title"></i>
                    <h5>Biểu đồ thống kê</h5>
                </div>
            </div>
            <div class="card-content">
                <div class="charts-grid">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h6>Thống kê 7 ngày gần nhất</h6>
                        </div>
                        <div class="chart-content">
                            <canvas id="dashboardChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <h6>Lượt xem dự án (30 ngày)</h6>
                        </div>
                        <div class="chart-content">
                            <canvas id="projectViewsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Stats -->
        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-info-circle icon-title"></i>
                    <h5>Thống kê chi tiết</h5>
                </div>
            </div>
            <div class="card-content">
                <div class="content-stats-grid">
                    <!-- Contact Stats -->
                    <div class="content-stat-card">
                        <div class="stat-card-header">
                            <h6><i class="fas fa-envelope"></i> Thống kê liên hệ</h6>
                        </div>
                        <div class="stat-card-content">
                            <div class="stat-item">
                                <span class="label">Hôm nay:</span>
                                <span class="value">{{ $contactStats['today'] }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Tuần này:</span>
                                <span class="value">{{ $contactStats['this_week'] }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Tháng này:</span>
                                <span class="value">{{ $contactStats['this_month'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Content Stats -->
                    <div class="content-stat-card">
                        <div class="stat-card-header">
                            <h6><i class="fas fa-newspaper"></i> Thống kê nội dung</h6>
                        </div>
                        <div class="stat-card-content">
                            <div class="stat-item">
                                <span class="label">Bài viết (Tổng/Đã xuất bản):</span>
                                <span class="value">{{ $contentStats['blogs']['total'] }}/{{ $contentStats['blogs']['published'] }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Dự án (Tổng/Đang hoạt động):</span>
                                <span class="value">{{ $contentStats['projects']['total'] }}/{{ $contentStats['projects']['active'] }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="label">Bài viết tháng này:</span>
                                <span class="value">{{ $contentStats['blogs']['this_month'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Pages -->
        <div class="content-card">
            <div class="card-top">
                <div class="card-title">
                    <i class="fas fa-trophy icon-title"></i>
                    <h5>Trang được xem nhiều nhất (30 ngày)</h5>
                </div>
            </div>
            <div class="card-content">
                @if($topPages->count() > 0)
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th class="column-small">Thứ hạng</th>
                                    <th class="column-large">Trang</th>
                                    <th class="column-medium">Lượt xem</th>
                                    <th class="column-medium">Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topPages as $index => $page)
                                    <tr>
                                        <td class="text-center">
                                            <span class="rank-badge">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="item-title">
                                            <strong>{{ ucfirst($page->page_name) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ number_format($page->total_views) }}</span>
                                        </td>
                                        <td>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: {{ ($page->total_views / $topPages->first()->total_views) * 100 }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Chưa có dữ liệu thống kê</h4>
                        <p>Dữ liệu sẽ hiển thị sau khi có người truy cập website.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .stat-card.highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: #007bff;
            color: white;
        }

        .stat-card.highlight .stat-icon {
            background: rgba(255,255,255,0.2);
        }

        .stat-content h3 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #333;
        }

        .stat-card.highlight .stat-content h3 {
            color: white;
        }

        .stat-content p {
            margin: 5px 0 0 0;
            color: #6c757d;
            font-size: 13px;
        }

        .stat-card.highlight .stat-content p {
            color: rgba(255,255,255,0.8);
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }

        .chart-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e9ecef;
        }

        .chart-header h6 {
            margin: 0 0 15px 0;
            color: #333;
            font-weight: 600;
        }

        .content-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .content-stat-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e9ecef;
        }

        .stat-card-header h6 {
            margin: 0 0 15px 0;
            color: #333;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-card-content .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .stat-card-content .stat-item:last-child {
            border-bottom: none;
        }

        .stat-item .label {
            color: #6c757d;
            font-size: 14px;
        }

        .stat-item .value {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        .rank-badge {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #007bff;
            color: white;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            font-size: 14px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #0056b3);
            transition: width 0.3s ease;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .empty-state h4 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .chart-container {
                padding: 15px;
            }
            
            .content-stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-content h3 {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .stat-content h3 {
                font-size: 18px;
            }
            
            .stat-content p {
                font-size: 12px;
            }
            
            .chart-header h6 {
                font-size: 14px;
            }
            
            .stat-card-header h6 {
                font-size: 14px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart data
        const chartData = @json($chartData);
        const projectViewsData = @json($projectViewsData);
        
        // Create main dashboard chart
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(item => item.date),
                datasets: [{
                    label: 'Lượt xem trang',
                    data: chartData.map(item => item.views),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Liên hệ mới',
                    data: chartData.map(item => item.contacts),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Create project views chart
        const projectCtx = document.getElementById('projectViewsChart').getContext('2d');
        
        // Generate labels for 30 days
        const projectLabels = [];
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            projectLabels.push(date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' }));
        }
        
        // Generate colors for each project
        const colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
        ];
        
        const projectDatasets = projectViewsData.map((project, index) => ({
            label: project.name.length > 20 ? project.name.substring(0, 20) + '...' : project.name,
            data: project.views,
            borderColor: colors[index % colors.length],
            backgroundColor: colors[index % colors.length] + '20',
            tension: 0.4,
            fill: false,
            pointRadius: 2,
            pointHoverRadius: 4
        }));
        
        new Chart(projectCtx, {
            type: 'line',
            data: {
                labels: projectLabels,
                datasets: projectDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            title: function(context) {
                                return 'Ngày ' + context[0].label;
                            },
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' lượt xem';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Ngày'
                        },
                        ticks: {
                            maxTicksLimit: 10
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Lượt xem'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    </script>
@endpush
