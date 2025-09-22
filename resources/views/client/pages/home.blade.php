@extends('client.layouts.app')
@section('title', 'Lịch âm hôm nay - Xem lịch âm')
@section('description', 'Xem lịch âm hôm nay, ngày giờ hoàng đạo, ngày tốt xấu chính xác nhất cho người Việt Nam')
@section('keywords', 'lịch âm, lịch âm hôm nay, giờ hoàng đạo, ngày tốt xấu, âm lịch')

@section('content')
    <div class="lunar-calendar-container">
        <div class="container-custom">
            <!-- Main Title -->
            <div class="main-title-section">
                <div class="container-custom">
                    <h1 class="main-title">Lịch âm hôm nay</h1>
                    <button class="view-lunar-btn">Xem lịch âm hôm nay</button>
                </div>
            </div>

            <!-- Current Date Info -->
            <x-current-date-info :lunarInfo="$lunarInfo" :sexagenary="$sexagenary" :dayFortune="$dayFortune" :auspiciousHours="$auspiciousHours" />
        </div>

        <!-- Main Content Layout -->
        <div class="main-content-layout">
            <div class="container-custom">
                <div class="row g-4">
                    <!-- Top Section - Large Date Display -->
                    <div class="col-12 mt-5">
                        <x-date-card :lunarInfo="$lunarInfo" :sexagenary="$sexagenary" :solarTerm="$solarTerm" :year="date('Y')"
                            :month="date('m')" :day="date('d')" :isToday="true" :auspiciousHours="$auspiciousHours" />
                    </div>

                    <!-- Bottom Section - Monthly Calendar -->
                    <div class="col-12">
                        <x-monthly-calendar :monthCalendar="$monthCalendar" :year="date('Y')" :month="date('m')" />
                    </div>

                    <!-- Fortune Detail Section -->
                    @if (!empty($vncalDetail))
                        <div class="col-12">
                            <div class="fortune-detail-section">
                                <div class="section-header">
                                    <h3 class="section-title">Xem tốt xấu ngày {{ date('d') }} tháng {{ date('m') }}
                                    </h3>
                                </div>
                                <div class="fortune-detail-content">
                                    {!! $vncalDetail !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="container-custom">
            <div class=" rounded-4 bg-white p-4 mt-3" style="height: auto !important;">
                <div class="col-md-12" style="height: auto !important; min-height: 0px !important;">
                    <h3 class="fw-bold">Nguồn gốc lịch âm</h3>
                    <p><b class="color-1 fw-bold">Lịch âm</b> hay còn gọi là <b class="color-1 fw-bold">lịch vạn niên</b> là
                        loại lịch dựa trên các chu kỳ của tuần trăng.
                        Loại
                        lịch duy nhất. Trên thực tế lịch âm là lịch của hồi giáo, trong đó mỗi năm chỉ chứa đúng 12 tháng
                        Mặt
                        Trăng. Đặc trưng của lịch âm thuần túy, như trong trường hợp của lịch Hồi giáo, là ở chỗ lịch này là
                        sự
                        liên tục của chu kỳ trăng tròn và hoàn toàn không gắn liền với các mùa.
                        Vì vậy <a class="text-decoration-none color-1 fw-bold" href="{{ route('home') }}"> năm âm lịch</a>
                        Hồi giáo ngắn hơn mỗi
                        năm
                        <b class="color-1 fw-bold">dương lịch</b> khoảng 11 hay 12 ngày, và chỉ trở lại vị trí ăn khớp với năm <b class="color-1 fw-bold">dương lịch</b> sau
                        mỗi
                        33 hoặc 34 năm Hồi giáo. Lịch Hồi giáo được sử dụng chủ yếu cho các mục đích tín ngưỡng tôn giáo.
                        Tại Ả
                        Rập Saudi lịch cũng được sử dụng cho các mục đích thương mại.
                    </p>
                    <p>Phần lớn các loại lịch khác, dù được gọi là <i>"âm lịch"</i> hay lịch vạn niên, trên thực tế chính là
                        <b class="color-1 fw-bold">âm dương lịch</b>. Điều này có nghĩa là trong các loại lịch đó, các tháng được duy trì theo chu
                        kỳ
                        của Mặt Trăng, nhưng đôi khi các tháng nhuận lại được thêm vào theo một số quy tắc nhất định để điều
                        chỉnh các chu kỳ trăng cho ăn khớp lại với năm <b class="color-1 fw-bold">dương lịch</b>. Hiện nay, trong tiếng Việt, khi
                        nói
                        tới âm lịch thì người ta nghĩ tới loại lịch được lập dựa trên các cơ sở và nguyên tắc của lịch Trung
                        Quốc, nhưng có sự chỉnh sửa lại theo UTC+7 thay vì UTC+8. Nó là một loại <b class="color-1 fw-bold">âm dương lịch</b> theo
                        sát
                        nghĩa chứ không phải âm lịch thuần túy. Do cách tính âm lịch đó khác với Trung Quốc cho nên Tết
                        Nguyên
                        đán của người Việt Nam đôi khi không hoàn toàn trùng với Xuân tiết của người Trung Quốc và các quốc
                        gia
                        chịu ảnh hưởng bởi văn hóa Trung Hoa và vòng Văn hóa chữ Hán khác.
                    </p>
                    <p>Do lịch âm thuần túy chỉ có 12 tháng âm lịch (tháng giao hội) trong mỗi năm, nên chu kỳ này (354,367
                        ngày) đôi khi cũng được gọi là năm âm lịch.</p>
                </div>
                <div class="col-md-12">
                    <h3 class="fw-bold">Âm dương lịch</h3>
                    <p><b class="color-1 fw-bold">Âm dương lịch</b> là loại lịch được nhiều nền văn hóa sử dụng, trong đó ngày tháng của lịch chỉ ra
                        cả
                        pha Mặt Trăng (hay tuần trăng) và thời gian của năm Mặt Trời (<b class="color-1 fw-bold">dương lịch</b>). Nếu năm Mặt Trời
                        được
                        định nghĩa như là năm chí tuyến thì <b class="color-1 fw-bold">âm dương lịch</b> sẽ cung cấp chỉ thị về mùa; nếu nó được
                        tính
                        theo năm thiên văn thì lịch sẽ dự báo chòm sao mà gần đó trăng tròn (điểm vọng) có thể xảy ra. Thông
                        thường luôn có yêu cầu bổ sung buộc một năm chỉ chứa một số tự nhiên các tháng, trong phần lớn các
                        năm
                        là 12 tháng nhưng cứ sau mỗi 2 (hay 3) năm lại có một năm với 13 tháng.</p>
                </div>
            </div>

        </div>
    @endsection

    @push('styles')
        @vite('resources/assets/frontend/css/home.css')
    @endpush

    @push('scripts')
        <script>
            // Animation on scroll
            const homeObserverOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const homeObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-visible');
                    }
                });
            }, homeObserverOptions);

            // Observe all animated elements
            document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
                homeObserver.observe(el);
            });

            // Real-time clock update
            function updateClock() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('vi-VN');
                const dateString = now.toLocaleDateString('vi-VN');

                // Update any clock elements if they exist
                const clockElements = document.querySelectorAll('.current-time');
                clockElements.forEach(el => {
                    el.textContent = timeString;
                });
            }

            // Update clock every second
            setInterval(updateClock, 1000);
            updateClock(); // Initial call

            // Function to select date and navigate to calendar page
            function selectDate(day) {
                const currentDate = new Date();
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth() + 1;

                window.location.href = `/calendar/${year}/${month}/${day}`;
            }

            function navigateDay(direction) {
                const currentDate = new Date();
                currentDate.setDate(currentDate.getDate() + direction);

                const year = currentDate.getFullYear();
                const month = currentDate.getMonth() + 1;
                const day = currentDate.getDate();

                window.location.href = `/calendar/${year}/${month}/${day}`;
            }

            function navigateMonth(direction) {
                const currentDate = new Date();
                currentDate.setMonth(currentDate.getMonth() + direction);

                const year = currentDate.getFullYear();
                const month = currentDate.getMonth() + 1;

                window.location.href = `/calendar/${year}/${month}`;
            }
        </script>
    @endpush
