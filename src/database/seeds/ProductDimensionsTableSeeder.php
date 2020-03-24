<?php

use Illuminate\Database\Seeder;
use Faker\Generator;

class ProductDimensionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
    	foreach (range(1,5) as $index) {
	        DB::table('product_dimensions')->insert([
	            'name' => $faker->numerify('#00x#00')
	        ]);
	    }
    }
}

