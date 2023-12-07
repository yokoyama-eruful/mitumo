<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('company')->comment('取扱代理店');
            $table->string('address')->comment('住所');
            $table->string('phone_number')->comment('TEL（電話番号）');
            $table->string('staff_name')->comment('担当者');
            $table->string('contractee')->comment('ご契約者');
            $table->string('location')->comment('所在地');

            $table->float('floor_area')->nullable()->comment('延床面積');
            $table->float('exclusive_area')->nullable()->comment('専有面積');
            $table->string('structure')->comment('構造');
            $table->string('other_structure')->nullable()->comment('その他構造');
            $table->string('pillar')->nullable()->comment('柱無時');
            $table->string('other_pillar')->nullable()->comment('その他柱無時');
            $table->string('fire_resistance')->nullable()->comment('耐火基準');
            $table->string('other_fire_resistance')->nullable()->comment('その他耐火基準');
            $table->string('usage')->comment('用法');
            $table->string('other_usage')->nullable()->comment('その他用法');
            $table->integer('floor')->comment('階');
            $table->integer('building')->comment('棟');
            $table->string('other')->nullable()->comment('その他');
            
            $table->string('property_type')->comment('物件');
            $table->string('rank')->comment('級別');
            $table->date('start_date')->comment('始期日');
            $table->integer('building_year')->comment('建築年');
            $table->integer('building_age')->comment('築年数');
            $table->string('pay_method')->comment('払込方法');
            $table->integer('insurance_year')->nullable()->comment('共済期間_年');
            $table->integer('insurance_month')->nullable()->comment('共済期間_月');
            $table->integer('insurance_day')->nullable()->comment('共済期間_日');

            $table->string('assess_item')->nullable()->comment('評価項目');

            $table->json('property_detail')->comment('物件の詳細情報');
            $table->json('assess_detail')->comment('評価項目の詳細情報');

            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
