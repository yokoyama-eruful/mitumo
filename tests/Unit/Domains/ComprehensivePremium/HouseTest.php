<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\ComprehensivePremium;

use App\Domains\ComprehensivePremium;
use App\Models\Estimate;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class HouseTest extends TestCase
{
    #[DataProvider('rateCases')]
    public function test_house_rates_match_the_building_age_and_grade(
        int $buildingAge,
        string $rank,
        float $buildingRate,
        float $householdRate,
    ): void {
        $estimate = new Estimate([
            'property_type' => '住宅',
            'building_age' => $buildingAge,
            'rank' => $rank,
            'start_date' => '2026-04-01',
        ]);

        $rates = new ComprehensivePremium($estimate);

        $this->assertSame($buildingRate, $rates->getRate('建物'));
        $this->assertSame($householdRate, $rates->getRate('家財'));
        $this->assertNull($rates->getRate('動産2'));
        $this->assertNull($rates->getRate('動産3'));
    }

    public static function rateCases(): array
    {
        return [
            'M grade, age 1' => [1, 'M', 0.13, 0.35],
            'M grade, age 19' => [19, 'M', 0.13, 0.35],
            'M grade, age 20' => [20, 'M', 0.17, 0.35],
            'M grade, age 24' => [24, 'M', 0.17, 0.35],
            'M grade, age 25' => [25, 'M', 0.29, 0.35],
            'M grade, age over cap' => [26, 'M', 0.29, 0.35],
            'T grade, age 1' => [1, 'T', 0.24, 0.58],
            'T grade, age 19' => [19, 'T', 0.24, 0.58],
            'T grade, age 20' => [20, 'T', 0.3, 0.58],
            'T grade, age 24' => [24, 'T', 0.3, 0.58],
            'T grade, age 25' => [25, 'T', 0.47, 0.58],
            'T grade, age over cap' => [26, 'T', 0.47, 0.58],
            'H grade, age 1' => [1, 'H', 0.48, 0.9],
            'H grade, age 19' => [19, 'H', 0.48, 0.9],
            'H grade, age 20' => [20, 'H', 0.48, 0.9],
            'H grade, age 24' => [24, 'H', 0.48, 0.9],
            'H grade, age 25' => [25, 'H', 0.8, 0.9],
            'H grade, age over cap' => [26, 'H', 0.8, 0.9],
        ];
    }
}
