<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\ComprehensivePremium;

use App\Domains\ComprehensivePremium;
use App\Models\Estimate;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class StandardTest extends TestCase
{
    #[DataProvider('rateCases')]
    public function test_rates_match_the_start_date_and_building_age(
        int $buildingAge,
        string $rank,
        string $startDate,
        array $expectedRates,
    ): void {
        $estimate = new Estimate([
            'property_type' => '普通',
            'building_age' => $buildingAge,
            'rank' => $rank,
            'start_date' => $startDate,
        ]);

        $rates = new ComprehensivePremium($estimate);

        foreach ($expectedRates as $purpose => $expectedRate) {
            $this->assertSame($expectedRate, $rates->getRate($purpose), "{$startDate} / {$buildingAge}年 / {$rank} / {$purpose}");
        }
    }

    public static function rateCases(): array
    {
        return [
            'grade 1, young building, current rates' => [
                1,
                '1級',
                '2026-04-01',
                ['建物' => 0.25, '家財' => 0.5, '動産2' => 0.43, '動産3' => 0.14],
            ],
            'grade 1, age 19, current rates' => [
                19,
                '1級',
                '2026-04-01',
                ['建物' => 0.25, '家財' => 0.5, '動産2' => 0.43, '動産3' => 0.14],
            ],
            'grade 1, age 20, current rates' => [
                20,
                '1級',
                '2026-04-01',
                ['建物' => 0.33, '家財' => 0.5, '動産2' => 0.43, '動産3' => 0.14],
            ],
            'grade 1, old building, before rate change' => [
                26,
                '1級',
                '2026-03-31',
                ['建物' => 0.25, '家財' => 0.5, '動産2' => 0.43, '動産3' => 0.14],
            ],
            'grade 1, old building, rate change is effective on its start date' => [
                26,
                '1級',
                '2026-04-01',
                ['建物' => 0.33, '家財' => 0.5, '動産2' => 0.43, '動産3' => 0.14],
            ],
            'grade 2, maximum supported age' => [
                25,
                '2級',
                '2026-04-01',
                ['建物' => 0.33, '家財' => 0.5, '動産2' => 0.43, '動産3' => 0.14],
            ],
            'grade 2, age 20, current rates' => [
                20,
                '2級',
                '2026-04-01',
                ['建物' => 0.33, '家財' => 0.5, '動産2' => 0.43, '動産3' => 0.14],
            ],
            'grade 3, young building, current rates' => [
                1,
                '3級',
                '2026-04-01',
                ['建物' => 0.57, '家財' => 0.74, '動産2' => 0.65, '動産3' => 0.33],
            ],
            'grade 3, age 19, current rates' => [
                19,
                '3級',
                '2026-04-01',
                ['建物' => 0.57, '家財' => 0.74, '動産2' => 0.65, '動産3' => 0.33],
            ],
            'grade 3, age 20, current rates' => [
                20,
                '3級',
                '2026-04-01',
                ['建物' => 0.74, '家財' => 0.74, '動産2' => 0.65, '動産3' => 0.33],
            ],
            'grade 3, old building, before rate change' => [
                25,
                '3級',
                '2026-03-31',
                ['建物' => 0.57, '家財' => 0.74, '動産2' => 0.59, '動産3' => 0.3],
            ],
            'grade 3, old building, rate change is effective on its start date' => [
                25,
                '3級',
                '2026-04-01',
                ['建物' => 0.74, '家財' => 0.74, '動産2' => 0.65, '動産3' => 0.33],
            ],
        ];
    }

    public function test_blank_building_age_uses_the_maximum_age_rate(): void
    {
        $estimate = new Estimate([
            'property_type' => '普通',
            'building_age' => null,
            'rank' => '1級',
            'start_date' => '2026-04-01',
        ]);

        $rates = new ComprehensivePremium($estimate);

        $this->assertSame(25, $rates->buildingAge);
        $this->assertSame(0.33, $rates->getRate('建物'));
    }

    public function test_unknown_purposes_and_unmatched_rates_return_null(): void
    {
        $estimate = new Estimate([
            'property_type' => '存在しない物件',
            'building_age' => 1,
            'rank' => '1級',
            'start_date' => '2026-04-01',
        ]);

        $rates = new ComprehensivePremium($estimate);

        $this->assertNull($rates->getRate('建物'));
        $this->assertNull($rates->getRate('存在しない目的'));
    }
}
