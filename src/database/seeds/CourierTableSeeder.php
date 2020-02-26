<?php

use Illuminate\Database\Seeder;
use Faker\Generator;

class CourierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
    	foreach (range(1,10) as $index) {
	        DB::table('couriers')->insert([
	            'name' => $faker->numerify('Courier ##')              
	        ]);
	    }
    }
}
