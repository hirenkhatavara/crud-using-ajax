<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HobbiesTableSeeder extends Seeder
{
    public function run()
    {
        $hobbies = [
            ['name' => 'Reading'],
            ['name' => 'Traveling'],
            ['name' => 'Swimming'],
            ['name' => 'Cooking'],
            ['name' => 'Gardening'],
            ['name' => 'Drawing'],
            ['name' => 'Writing'],
            ['name' => 'Cycling'],
            ['name' => 'Hiking'],
            ['name' => 'Fishing'],
        ];

        DB::table('hobbies')->insert($hobbies);
    }
}
