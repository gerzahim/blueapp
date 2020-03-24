<?php

use Illuminate\Database\Seeder;
use Faker\Generator;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
    	foreach (range(1,5) as $index) {
	        DB::table('categories')->insert([
                'name' => $faker->numerify('Category ######')
	        ]);
	    }
    }
}

