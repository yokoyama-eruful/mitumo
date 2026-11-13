<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\Assess;

use App\Domains\Assess\AssessPiloti;
use App\Models\Estimate;
use App\Models\Futsu2;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class AssessPilotiTest extends TestCase
{
    #[DataProvider('estimateModels')]
    public function test_piloti_reprocurement_calculation_uses_the_selected_rate(string $modelClass): void
    {
        $estimate = new $modelClass([
            'floor_area' => 100,
            'building_age' => 10,
            'building_year' => 2000,
            'assess_detail' => [
                'assess_usage' => '専用住宅（柱あり）',
                'assess_structure' => '木造',
                'piloti_floor_area' => 20,
                'rate_version' => 'new',
                'reprocure_rate' => 95,
            ],
        ]);

        $assess = new AssessPiloti($estimate);
        $amount = $assess->getAllFloorAreaReProcure('A');

        $this->assertSame('17200.00', $amount['100%']);
        $this->assertSame('16340.00', $amount['80%']);
    }

    public static function estimateModels(): array
    {
        return [
            'ordinary estimate' => [Estimate::class],
            'ordinary 2 estimate' => [Futsu2::class],
        ];
    }
}
