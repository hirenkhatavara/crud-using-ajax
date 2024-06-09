<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Technology'],
            ['name' => 'Health'],
            ['name' => 'Finance'],
            ['name' => 'Education'],
            ['name' => 'Entertainment'],
            ['name' => 'Sports'],
            ['name' => 'Travel'],
            ['name' => 'Lifestyle'],
            ['name' => 'Food'],
            ['name' => 'Fashion'],
        ];

        DB::table('categories')->insert($categories);
    }
}
