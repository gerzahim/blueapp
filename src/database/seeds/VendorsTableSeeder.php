<?php

use Illuminate\Database\Seeder;
use Faker\Generator;


class VendorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        foreach (range(1,10) as $index) {
            DB::table('vendors')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->freeEmail ,
                'contact_person' => $faker->name,
                'notes' => $faker->text($maxNbChars = 100),
                'address1' => $faker->address,
                'address2' => $faker->buildingNumber,
                'city' => $faker->city,
                'state' => $faker->state,
                'postal_code' => $faker->postcode,
                'country' => $faker->country,
                'phone' => $faker->phoneNumber,
                'mobile' => $faker->phoneNumber,
                'created_at' => $faker->date($format = 'Y-m-d ', $max = 'now').$faker->time($format = 'H:i:s', $max = 'now'),
                'updated_at' => $faker->dateTime($max = 'now', $timezone = null)
            ]);
        }

    }
}
