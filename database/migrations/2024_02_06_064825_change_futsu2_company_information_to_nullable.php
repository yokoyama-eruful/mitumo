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
        Schema::table('estimate_futsu2', function (Blueprint $table) {
            $table->string('company')->nullable(true)->change();
            $table->string('address')->nullable(true)->change();
            $table->string('phone_number')->nullable(true)->change();
            $table->string('staff_name')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimate_futsu2', function (Blueprint $table) {
            $table->string('company')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('phone_number')->nullable(false)->change();
            $table->string('staff_name')->nullable(false)->change();
        });
    }
};
