<?php

use Illuminate\Database\Seeder;

class ContactTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contact_types')->insert([
            'id'=> 1,
            'name'=> 'vendor'
        ]);
        DB::table('contact_types')->insert([
            'id'=> 2,
            'name'=> 'customer'
        ]);
    }
}
