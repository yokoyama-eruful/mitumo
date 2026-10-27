<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\ComprehensivePremium;

use App\Domains\ComprehensivePremium;
use App\Models\Estimate;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class FactoryTest extends TestCase
{
    #[DataProvider('rankCases')]
    public function test_factory_rates_are_zero_for_each_grade(string $rank): void
    {
        $estimate = new Estimate([
            'property_type' => '工場',
            'building_age' => 1,
            'rank' => $rank,
            'start_date' => '2026-04-01',
        ]);

        $rates = new ComprehensivePremium($estimate);

        foreach (['建物', '家財', '動産2', '動産3'] as $purpose) {
            $this->assertSame(0.0, $rates->getRate($purpose), "{$rank} / {$purpose}");
        }
    }

    public static function rankCases(): array
    {
        return [
            'grade 1' => ['1級'],
            'grade 2' => ['2級'],
            'grade 3' => ['3級'],
        ];
    }
}
