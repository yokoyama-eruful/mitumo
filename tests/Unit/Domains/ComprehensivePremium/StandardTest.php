<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\ComprehensivePremium;

use App\Domains\ComprehensivePremium;
use App\Models\Estimate;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class StandardTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
    }

    //////////////////////////////////////////////////
    // 普通
    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：普通
     * 　築年数：1年
     * 　級数　：1級
     */
    public function test_普通1年1級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '普通';
        $estimate->building_age = 1;
        $estimate->rank = '1級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.25, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.5, $comprehensivePremium->getRate('家財'));

        $this->assertEquals(0.43, $comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.14, $comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：普通
     * 　築年数：26年
     * 　級数　：1級
     */
    public function test_普通25年1級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '普通';
        $estimate->building_age = 26;
        $estimate->rank = '1級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.25, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.5, $comprehensivePremium->getRate('家財'));

        $this->assertEquals(0.43, $comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.14, $comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：普通
     * 　築年数：1年
     * 　級数　：2級
     */
    public function test_普通1年2級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '普通';
        $estimate->building_age = 1;
        $estimate->rank = '2級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.25, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.5, $comprehensivePremium->getRate('家財'));

        $this->assertEquals(0.43, $comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.14, $comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：普通
     * 　築年数：25年
     * 　級数　：2級
     */
    public function test_普通25年2級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '普通';
        $estimate->building_age = 25;
        $estimate->rank = '2級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.25, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.5, $comprehensivePremium->getRate('家財'));

        $this->assertEquals(0.43, $comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.14, $comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：普通
     * 　築年数：1年
     * 　級数　：3級
     */
    public function test_普通1年3級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '普通';
        $estimate->building_age = 1;
        $estimate->rank = '3級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.57, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.74, $comprehensivePremium->getRate('家財'));

        $this->assertEquals(0.59, $comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.3, $comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：普通
     * 　築年数：25年
     * 　級数　：3級
     */
    public function test_普通25年3級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '普通';
        $estimate->building_age = 25;
        $estimate->rank = '3級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.57, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.74, $comprehensivePremium->getRate('家財'));

        $this->assertEquals(0.59, $comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.3, $comprehensivePremium->getRate('動産3'));
    }
}
