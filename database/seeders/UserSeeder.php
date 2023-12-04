<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'staff_id' => 'mitumori',
            'email' => 'example@example.com',
            'name' => 'エルフル太郎',
            'password' => Hash::make('korehatest'),
        ]);
    }
}
