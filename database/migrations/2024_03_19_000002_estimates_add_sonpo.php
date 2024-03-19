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
        Schema::table('estimates', function (Blueprint $table) {
            $table->boolean('is_sonpo_base_rate')->default(false);
            $table->float('sonpo_building_base_rate')->nullable();
            $table->float('sonpo_chattel_base_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('is_sonpo_base_rate');
            $table->dropColumn('sonpo_building_base_rate');
            $table->dropColumn('sonpo_chattel_base_rate');
        });
    }
};
