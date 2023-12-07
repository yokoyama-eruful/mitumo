<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\ComprehensivePremium;

use App\Domains\ComprehensivePremium;
use App\Models\Estimate;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class HouseTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
    }

    //////////////////////////////////////////////////
    //住宅M級
    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：１年
     * 　級数　：M
     */
    public function test_住宅1年Mの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 1;
        $estimate->rank = 'M';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.13, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.35, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：19年
     * 　級数　：M
     */
    public function test_住宅19年Mの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 19;
        $estimate->rank = 'M';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.13, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.35, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：20年
     * 　級数　：M
     */
    public function test_住宅20年Mの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 20;
        $estimate->rank = 'M';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.17, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.35, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：24年
     * 　級数　：M
     */
    public function test_住宅24年Mの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 24;
        $estimate->rank = 'M';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.17, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.35, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：25年
     * 　級数　：M
     */
    public function test_住宅25年Mの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 25;
        $estimate->rank = 'M';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.29, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.35, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：26年
     * 　級数　：M
     */
    public function test_住宅26年Mの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 26;
        $estimate->rank = 'M';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.29, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.35, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    //////////////////////////////////////////////////
    //住宅T級

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：１年
     * 　級数　：T
     */
    public function test_住宅1年Tの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 1;
        $estimate->rank = 'T';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.24, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.58, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：19年
     * 　級数　：T
     */
    public function test_住宅19年Tの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 19;
        $estimate->rank = 'T';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.24, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.58, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：20年
     * 　級数　：T
     */
    public function test_住宅20年Tの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 20;
        $estimate->rank = 'T';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.3, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.58, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：23年
     * 　級数　：T
     */
    public function test_住宅24年Tの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 24;
        $estimate->rank = 'T';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.3, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.58, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：25年
     * 　級数　：T
     */
    public function test_住宅25年Tの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 25;
        $estimate->rank = 'T';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.47, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.58, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：26年
     * 　級数　：M
     */
    public function test_住宅26年Tの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 26;
        $estimate->rank = 'T';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.47, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.58, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    //////////////////////////////////////////////////
    //住宅T級

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：１年
     * 　級数　：H
     */
    public function test_住宅1年Hの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 1;
        $estimate->rank = 'H';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.48, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.9, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：24年
     * 　級数　：H
     */
    public function test_住宅24年Hの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 24;
        $estimate->rank = 'H';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.48, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.9, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：25年
     * 　級数　：H
     */
    public function test_住宅25年Hの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 25;
        $estimate->rank = 'H';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.8, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.9, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }

    /**
     * 総合割増のテスト
     * 条件
     * 　物件　：住宅
     * 　築年数：26年
     * 　級数　：H
     */
    public function test_住宅26年Hの条件でのテスト(): void
    {
        $estimate = new Estimate();
        $estimate->property_type = '住宅';
        $estimate->building_age = 26;
        $estimate->rank = 'H';

        $comprehensivePremium = new ComprehensivePremium($estimate);

        $this->assertEquals(0.8, $comprehensivePremium->getRate('建物'));
        $this->assertEquals(0.9, $comprehensivePremium->getRate('家財'));

        $this->assertNull($comprehensivePremium->getRate('動産2'));
        $this->assertNull($comprehensivePremium->getRate('動産3'));
    }
}
