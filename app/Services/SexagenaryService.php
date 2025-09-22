<?php

namespace App\Services;

use LucNham\LunarCalendar\Sexagenary;
use LucNham\LunarCalendar\Terms\VnStemIdentifier;
use LucNham\LunarCalendar\Terms\VnBranchIdentifier;
use LucNham\LunarCalendar\LunarDateTime;
use Illuminate\Support\Facades\Cache;

class SexagenaryService
{
    /**
     * Lấy thông tin can chi hiện tại
     */
    public function getCurrentSexagenary()
    {
        $cacheKey = 'sexagenary_current_' . date('Y-m-d');
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () {
            $lunar = new LunarDateTime();
            $sexagenary = new Sexagenary(
                lunar: $lunar,
                stemIdetifier: VnStemIdentifier::class,
                branchIdentifier: VnBranchIdentifier::class,
            );

            return [
                'day' => [
                    'stem' => $sexagenary->D->name,
                    'branch' => $sexagenary->d->name,
                    'combined' => $sexagenary->format('[D+]'),
                ],
                'month' => [
                    'stem' => $sexagenary->M->name,
                    'branch' => $sexagenary->m->name,
                    'combined' => $sexagenary->format('[M+]'),
                ],
                'year' => [
                    'stem' => $sexagenary->Y->name,
                    'branch' => $sexagenary->y->name,
                    'combined' => $sexagenary->format('[Y+]'),
                ],
                'hour' => [
                    'stem' => $sexagenary->H->name,
                    'branch' => $sexagenary->h->name,
                    'combined' => $sexagenary->format('[H+]'),
                ],
                'week' => [
                    'stem' => $sexagenary->W->name,
                    'branch' => $sexagenary->w->name,
                    'combined' => $sexagenary->format('[W+]'),
                ],
            ];
        });
    }

    /**
     * Lấy can chi theo ngày âm lịch
     */
    public function getSexagenaryByLunarDate($lunarDate)
    {
        $lunar = new LunarDateTime($lunarDate);
        $sexagenary = new Sexagenary(
            lunar: $lunar,
            stemIdetifier: VnStemIdentifier::class,
            branchIdentifier: VnBranchIdentifier::class,
        );

        return [
            'day' => $sexagenary->format('[D+]'),
            'month' => $sexagenary->format('[M+]'),
            'year' => $sexagenary->format('[Y+]'),
            'hour' => $sexagenary->format('[H+]'),
        ];
    }

    /**
     * Lấy thông tin chi tiết về can
     */
    public function getStemInfo($stem)
    {
        $stems = [
            'Giáp' => [
                'name' => 'Giáp',
                'element' => 'Mộc',
                'direction' => 'Đông',
                'color' => 'Xanh',
                'personality' => 'Lãnh đạo, quyết đoán',
            ],
            'Ất' => [
                'name' => 'Ất',
                'element' => 'Mộc',
                'direction' => 'Đông',
                'color' => 'Xanh nhạt',
                'personality' => 'Dịu dàng, kiên nhẫn',
            ],
            'Bính' => [
                'name' => 'Bính',
                'element' => 'Hỏa',
                'direction' => 'Nam',
                'color' => 'Đỏ',
                'personality' => 'Nhiệt tình, sôi nổi',
            ],
            'Đinh' => [
                'name' => 'Đinh',
                'element' => 'Hỏa',
                'direction' => 'Nam',
                'color' => 'Đỏ nhạt',
                'personality' => 'Tinh tế, cẩn thận',
            ],
            'Mậu' => [
                'name' => 'Mậu',
                'element' => 'Thổ',
                'direction' => 'Trung tâm',
                'color' => 'Vàng',
                'personality' => 'Ổn định, đáng tin cậy',
            ],
            'Kỷ' => [
                'name' => 'Kỷ',
                'element' => 'Thổ',
                'direction' => 'Trung tâm',
                'color' => 'Vàng nhạt',
                'personality' => 'Cẩn thận, tỉ mỉ',
            ],
            'Canh' => [
                'name' => 'Canh',
                'element' => 'Kim',
                'direction' => 'Tây',
                'color' => 'Trắng',
                'personality' => 'Cương quyết, mạnh mẽ',
            ],
            'Tân' => [
                'name' => 'Tân',
                'element' => 'Kim',
                'direction' => 'Tây',
                'color' => 'Trắng nhạt',
                'personality' => 'Tinh tế, sắc sảo',
            ],
            'Nhâm' => [
                'name' => 'Nhâm',
                'element' => 'Thủy',
                'direction' => 'Bắc',
                'color' => 'Đen',
                'personality' => 'Sâu sắc, bí ẩn',
            ],
            'Quý' => [
                'name' => 'Quý',
                'element' => 'Thủy',
                'direction' => 'Bắc',
                'color' => 'Đen nhạt',
                'personality' => 'Linh hoạt, thích ứng',
            ],
        ];

        return $stems[$stem] ?? null;
    }

    /**
     * Lấy thông tin chi tiết về chi
     */
    public function getBranchInfo($branch)
    {
        $branches = [
            'Tý' => [
                'name' => 'Tý',
                'animal' => 'Chuột',
                'time' => '23:00-01:00',
                'direction' => 'Bắc',
                'element' => 'Thủy',
                'personality' => 'Thông minh, nhanh nhẹn',
            ],
            'Sửu' => [
                'name' => 'Sửu',
                'animal' => 'Trâu',
                'time' => '01:00-03:00',
                'direction' => 'Bắc Đông Bắc',
                'element' => 'Thổ',
                'personality' => 'Chăm chỉ, kiên trì',
            ],
            'Dần' => [
                'name' => 'Dần',
                'animal' => 'Hổ',
                'time' => '03:00-05:00',
                'direction' => 'Đông Bắc',
                'element' => 'Mộc',
                'personality' => 'Mạnh mẽ, dũng cảm',
            ],
            'Mão' => [
                'name' => 'Mão',
                'animal' => 'Mèo',
                'time' => '05:00-07:00',
                'direction' => 'Đông',
                'element' => 'Mộc',
                'personality' => 'Dịu dàng, tinh tế',
            ],
            'Thìn' => [
                'name' => 'Thìn',
                'animal' => 'Rồng',
                'time' => '07:00-09:00',
                'direction' => 'Đông Nam',
                'element' => 'Thổ',
                'personality' => 'Cao quý, mạnh mẽ',
            ],
            'Tỵ' => [
                'name' => 'Tỵ',
                'animal' => 'Rắn',
                'time' => '09:00-11:00',
                'direction' => 'Nam Đông Nam',
                'element' => 'Hỏa',
                'personality' => 'Khôn ngoan, bí ẩn',
            ],
            'Ngọ' => [
                'name' => 'Ngọ',
                'animal' => 'Ngựa',
                'time' => '11:00-13:00',
                'direction' => 'Nam',
                'element' => 'Hỏa',
                'personality' => 'Nhiệt tình, năng động',
            ],
            'Mùi' => [
                'name' => 'Mùi',
                'animal' => 'Dê',
                'time' => '13:00-15:00',
                'direction' => 'Nam Tây Nam',
                'element' => 'Thổ',
                'personality' => 'Dịu dàng, nghệ sĩ',
            ],
            'Thân' => [
                'name' => 'Thân',
                'animal' => 'Khỉ',
                'time' => '15:00-17:00',
                'direction' => 'Tây Nam',
                'element' => 'Kim',
                'personality' => 'Thông minh, hài hước',
            ],
            'Dậu' => [
                'name' => 'Dậu',
                'animal' => 'Gà',
                'time' => '17:00-19:00',
                'direction' => 'Tây',
                'element' => 'Kim',
                'personality' => 'Cẩn thận, đúng giờ',
            ],
            'Tuất' => [
                'name' => 'Tuất',
                'animal' => 'Chó',
                'time' => '19:00-21:00',
                'direction' => 'Tây Bắc',
                'element' => 'Thổ',
                'personality' => 'Trung thành, bảo vệ',
            ],
            'Hợi' => [
                'name' => 'Hợi',
                'animal' => 'Lợn',
                'time' => '21:00-23:00',
                'direction' => 'Bắc Tây Bắc',
                'element' => 'Thủy',
                'personality' => 'Tốt bụng, hòa đồng',
            ],
        ];

        return $branches[$branch] ?? null;
    }

    /**
     * Lấy thông tin chi tiết về can chi kết hợp
     */
    public function getCombinedSexagenaryInfo($stem, $branch)
    {
        $stemInfo = $this->getStemInfo($stem);
        $branchInfo = $this->getBranchInfo($branch);
        
        if (!$stemInfo || !$branchInfo) {
            return null;
        }

        return [
            'combined' => "$stem $branch",
            'stem' => $stemInfo,
            'branch' => $branchInfo,
            'compatibility' => $this->getCompatibility($stemInfo['element'], $branchInfo['element']),
            'fortune' => $this->getFortune($stem, $branch),
        ];
    }

    /**
     * Kiểm tra tương hợp giữa can và chi
     */
    private function getCompatibility($stemElement, $branchElement)
    {
        $compatibilities = [
            'Mộc' => ['Hỏa', 'Thủy'],
            'Hỏa' => ['Thổ', 'Mộc'],
            'Thổ' => ['Kim', 'Hỏa'],
            'Kim' => ['Thủy', 'Thổ'],
            'Thủy' => ['Mộc', 'Kim'],
        ];

        $isCompatible = in_array($branchElement, $compatibilities[$stemElement] ?? []);
        
        return [
            'is_compatible' => $isCompatible,
            'level' => $isCompatible ? 'Tương hợp' : 'Không tương hợp',
            'description' => $isCompatible 
                ? 'Can chi tương hợp, mang lại may mắn' 
                : 'Can chi không tương hợp, cần cẩn thận',
        ];
    }

    /**
     * Lấy thông tin vận mệnh theo can chi
     */
    private function getFortune($stem, $branch)
    {
        $fortunes = [
            'Giáp Tý' => 'Ngày tốt, xuất hành thuận lợi',
            'Ất Sửu' => 'Ngày bình thường, nên cẩn thận',
            'Bính Dần' => 'Ngày tốt, gặp quý nhân',
            'Đinh Mão' => 'Ngày tốt, làm việc thuận lợi',
            'Mậu Thìn' => 'Ngày tốt, tài lộc dồi dào',
            'Kỷ Tỵ' => 'Ngày bình thường, tránh tranh cãi',
            'Canh Ngọ' => 'Ngày tốt, sức khỏe tốt',
            'Tân Mùi' => 'Ngày bình thường, cần kiên nhẫn',
            'Nhâm Thân' => 'Ngày tốt, thông minh sáng suốt',
            'Quý Dậu' => 'Ngày tốt, nghệ thuật phát triển',
            'Giáp Tuất' => 'Ngày tốt, trung thành đáng tin',
            'Ất Hợi' => 'Ngày tốt, hòa thuận gia đình',
        ];

        return $fortunes["$stem $branch"] ?? 'Thông tin chưa có sẵn';
    }

    /**
     * Lấy danh sách tất cả can
     */
    public function getAllStems()
    {
        return ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    }

    /**
     * Lấy danh sách tất cả chi
     */
    public function getAllBranches()
    {
        return ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
    }

    /**
     * Lấy chu kỳ 60 năm (Lục thập hoa giáp)
     */
    public function getSexagenaryCycle()
    {
        $stems = $this->getAllStems();
        $branches = $this->getAllBranches();
        $cycle = [];
        
        $stemIndex = 0;
        $branchIndex = 0;
        
        for ($i = 0; $i < 60; $i++) {
            $cycle[] = [
                'position' => $i + 1,
                'stem' => $stems[$stemIndex],
                'branch' => $branches[$branchIndex],
                'combined' => $stems[$stemIndex] . ' ' . $branches[$branchIndex],
            ];
            
            $stemIndex = ($stemIndex + 1) % 10;
            $branchIndex = ($branchIndex + 1) % 12;
        }
        
        return $cycle;
    }

    /**
     * Lấy thông tin can chi cho ngày cụ thể
     */
    public function getSexagenaryForDate($gregorianDate)
    {
        $cacheKey = 'sexagenary_date_' . $gregorianDate;
        
        return Cache::remember($cacheKey, 7 * 24 * 60, function () use ($gregorianDate) {
            $lunar = LunarDateTime::fromGregorian($gregorianDate);
            $sexagenary = new Sexagenary(
                lunar: $lunar,
                stemIdetifier: VnStemIdentifier::class,
                branchIdentifier: VnBranchIdentifier::class,
            );

            return [
                'day' => [
                    'stem' => $sexagenary->D->name,
                    'branch' => $sexagenary->d->name,
                    'combined' => $sexagenary->format('[D+]'),
                ],
                'month' => [
                    'stem' => $sexagenary->M->name,
                    'branch' => $sexagenary->m->name,
                    'combined' => $sexagenary->format('[M+]'),
                ],
                'year' => [
                    'stem' => $sexagenary->Y->name,
                    'branch' => $sexagenary->y->name,
                    'combined' => $sexagenary->format('[Y+]'),
                ],
                'hour' => [
                    'stem' => $sexagenary->H->name,
                    'branch' => $sexagenary->h->name,
                    'combined' => $sexagenary->format('[H+]'),
                ],
                'week' => [
                    'stem' => $sexagenary->W->name,
                    'branch' => $sexagenary->w->name,
                    'combined' => $sexagenary->format('[W+]'),
                ],
            ];
        });
    }
}
