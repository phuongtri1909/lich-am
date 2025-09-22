@extends('client.layouts.app')
@section('title', 'Đổi ngày âm dương - Xem lịch âm')
@section('description', 'Công cụ chuyển đổi ngày dương lịch sang âm lịch và ngược lại chính xác nhất cho người Việt Nam')
@section('keywords', 'đổi ngày âm dương, chuyển đổi lịch, dương lịch sang âm lịch, âm lịch sang dương lịch')

{{-- All data is now provided by the controller --}}

@section('content')
    <div class="converter-container">
        <div class="container-custom">
            <!-- Main Title -->
            <div class="main-title-section">
                <div class="container-custom">
                    <h1 class="main-title">Đổi ngày âm dương</h1>
                </div>
            </div>

            <x-breadcrumb :items="[
                ['label' => 'Trang chủ', 'url' => route('home')],
                ['label' => 'Đổi ngày âm dương', 'url' => route('converter')],
            ]" />

            <!-- Main Converter Layout -->
            <div class="converter-main-layout mt-4">
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-12 col-lg-6">
                        <!-- Converter Form -->
                        <div class="converter-form-section">
                            <div class="converter-card">
                                <div class="converter-header">
                                    <h3><i class="fas fa-exchange-alt"></i> Chuyển đổi ngày</h3>
                                </div>

                                <form id="converterForm" class="converter-form">
                                    @csrf
                                    <div class="form-group">
                                        <div class="conversion-type-info">
                                            <div class="type-display">
                                                <div class="type-icon">
                                                    <i class="fas fa-calendar-day"></i>
                                                </div>
                                                <div class="type-content">
                                                    <h4>Dương lịch → Âm lịch</h4>
                                                    <p>Chuyển từ ngày dương lịch sang âm lịch</p>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="type" id="conversionType" value="gregorian_to_lunar">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="dateInput">
                                            <i class="fas fa-calendar"></i>
                                            Chọn ngày
                                        </label>
                                        <div class="date-input-container">
                                            <input type="date" id="dateInput" name="date" class="form-control date-input"
                                                value="{{ $currentDate }}" required>
                                            <div class="date-format-hint">
                                                <span>Chọn ngày dương lịch để chuyển sang âm lịch</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="convert-btn">
                                            <i class="fas fa-exchange-alt"></i>
                                            <span>Chuyển đổi</span>
                                        </button>
                                        <button type="button" class="clear-btn" onclick="clearForm()">
                                            <i class="fas fa-eraser"></i>
                                            <span>Xóa</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Current Date Info -->
                        <div class="mt-4">
                            <x-current-date-info 
                                :lunarInfo="$lunarInfo"
                                :sexagenary="$sexagenary"
                                :dayFortune="$dayFortune"
                                :auspiciousHours="$auspiciousHours"
                                dateLabel="Ngày Dương Lịch:"
                                :dateValue="$currentDate"
                            />
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-12 col-lg-6">
                        <x-date-card 
                            :lunarInfo="$lunarInfo"
                            :sexagenary="$sexagenary"
                            :solarTerm="$solarTerm"
                            :year="$year"
                            :month="$month"
                            :day="$day"
                            :isToday="($year == date('Y') && $month == date('m') && $day == date('d'))"
                            :auspiciousHours="$auspiciousHours"
                        />
                    </div>
                </div>

                <!-- Monthly Calendar -->
                <div class="mt-4">
                    <x-monthly-calendar 
                        :monthCalendar="$monthCalendar"
                        :year="$year"
                        :month="$month"
                        :selectedDay="$day"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/home.css')
    <style>
        .converter-main-layout {
            margin-bottom: 2rem;
        }

        .converter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .converter-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .converter-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color-2);
            margin: 0;
        }

        .conversion-type-info {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .type-display {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .type-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color-2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
            flex-shrink: 0;
        }

        .type-content h4 {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 5px 0;
        }

        .type-content p {
            font-size: 12px;
            margin: 0;
            color: var(--text-secondary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .date-input-container {
            position: relative;
        }

        .date-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .date-input:focus {
            outline: none;
            border-color: var(--primary-color-2);
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
        }

        .date-format-hint {
            margin-top: 6px;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .convert-btn,
        .clear-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .convert-btn {
            background: var(--primary-color-2);
            color: white;
        }

        .convert-btn:hover {
            background: var(--primary-color-1);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .clear-btn {
            background: #6c757d;
            color: white;
        }

        .clear-btn:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        /* Lunar Date Picker Styles removed - only gregorian to lunar conversion */

        @media (max-width: 768px) {
            .conversion-type-selector {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .lunar-picker-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // No type selection needed - only gregorian to lunar

        // Form submission
        document.getElementById('converterForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('.convert-btn');
            const originalText = submitBtn.innerHTML;
            const dateInput = document.getElementById('dateInput');
            const conversionType = document.getElementById('conversionType').value;

            // Validate date selection - only gregorian to lunar
            if (!dateInput.value) {
                alert('Vui lòng chọn ngày dương lịch');
                return;
            }

            // Show loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Đang chuyển đổi...</span>';
            submitBtn.disabled = true;

            fetch('{{ route('converter.convert') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        displayResults(data.result);
                    } else {
                        console.error('Conversion failed:', data.message);
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Network error:', error);
                    alert('Có lỗi xảy ra khi chuyển đổi: ' + error.message);
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        function displayResults(result) {
            // Update the components with new data
            updateComponents(result);
        }

        function updateComponents(result) {
            // Only gregorian to lunar conversion - use gregorian date
            if (result.gregorian_date) {
                window.location.href = `{{ route('converter') }}?date=${result.gregorian_date}`;
            } else {
                console.error('No valid date found in result:', result);
                alert('Không thể xác định ngày từ kết quả chuyển đổi');
            }
        }

        function clearForm() {
            // Reset to current date
            const currentDate = new Date();
            const formattedDate = currentDate.toISOString().split('T')[0];
            document.getElementById('dateInput').value = formattedDate;
            window.location.href = `{{ route('converter') }}?date=${formattedDate}`;
        }

        // Navigation functions for components
        function navigateDay(direction) {
            const currentDate = new Date('{{ $currentDate }}');
            currentDate.setDate(currentDate.getDate() + direction);
            const formattedDate = currentDate.toISOString().split('T')[0];
            window.location.href = `{{ route('converter') }}?date=${formattedDate}`;
        }

        function navigateMonth(direction) {
            const currentDate = new Date('{{ $currentDate }}');
            currentDate.setMonth(currentDate.getMonth() + direction);
            const formattedDate = currentDate.toISOString().split('T')[0];
            window.location.href = `{{ route('converter') }}?date=${formattedDate}`;
        }

        function selectDate(day) {
            const currentDate = new Date('{{ $currentDate }}');
            currentDate.setDate(day);
            const formattedDate = currentDate.toISOString().split('T')[0];
            window.location.href = `{{ route('converter') }}?date=${formattedDate}`;
        }

        // Lunar Date Picker Functions removed - only gregorian to lunar conversion
    </script>
@endpush
