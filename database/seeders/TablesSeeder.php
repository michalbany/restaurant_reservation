<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tables')->delete();

        DB::table('tables')->insert([
            ['capacity' => 2],
            ['capacity' => 2],
            ['capacity' => 4],
            ['capacity' => 2],
            ['capacity' => 4],
            ['capacity' => 6],
            ['capacity' => 2]
        ]);
    }
}
