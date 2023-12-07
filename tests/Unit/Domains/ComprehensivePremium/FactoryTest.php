<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\ComprehensivePremium;

use App\Domains\ComprehensivePremium;
use App\Models\Estimate;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class FactoryTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
    }

    //////////////////////////////////////////////////
    // 工場
    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：工場
     * 　築年数：1年
     * 　級数　：1級
     */
    public function test_工場1年1級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '工場';
        $estimate->building_age = 1;
        $estimate->rank = '1級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertNotNull($comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('建物'));

        $this->assertNotNull($comprehensivePremium->getRate('家財'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('家財'));

        $this->assertNotNull($comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('動産2'));

        $this->assertNotNull($comprehensivePremium->getRate('動産3'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('動産3'));
    }

    //////////////////////////////////////////////////
    // 工場
    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：工場
     * 　築年数：1年
     * 　級数　：2級
     */
    public function test_工場1年2級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '工場';
        $estimate->building_age = 1;
        $estimate->rank = '2級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertNotNull($comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('建物'));

        $this->assertNotNull($comprehensivePremium->getRate('家財'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('家財'));

        $this->assertNotNull($comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('動産2'));

        $this->assertNotNull($comprehensivePremium->getRate('動産3'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('動産3'));
    }

    //////////////////////////////////////////////////
    // 工場
    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：工場
     * 　築年数：1年
     * 　級数　：3級
     */
    public function test_工場1年3級の条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '工場';
        $estimate->building_age = 1;
        $estimate->rank = '3級';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertNotNull($comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('建物'));

        $this->assertNotNull($comprehensivePremium->getRate('家財'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('家財'));

        $this->assertNotNull($comprehensivePremium->getRate('動産2'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('動産2'));

        $this->assertNotNull($comprehensivePremium->getRate('動産3'));
        $this->assertEquals(0.0, $comprehensivePremium->getRate('動産3'));
    }
}
