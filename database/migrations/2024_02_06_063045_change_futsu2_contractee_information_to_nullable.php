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
            $table->string('contractee')->nullable(true)->change();
            $table->string('location')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimate_futsu2', function (Blueprint $table) {
            $table->string('contractee')->nullable(false)->change();
            $table->string('location')->nullable(false)->change();
        });
    }
};
