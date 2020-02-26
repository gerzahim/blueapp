<?php

use Illuminate\Database\Seeder;
use Faker\Generator;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
    	foreach (range(1,10) as $index) {
	        DB::table('products')->insert([
                'name' => $faker->numerify('Product ##'),              
                'code' => $faker->numerify('sku_##'),
                'dimensions_id' => $faker->randomDigit(),
                'category_id' => $faker->randomDigit()
	        ]);
	    }
    }
}

