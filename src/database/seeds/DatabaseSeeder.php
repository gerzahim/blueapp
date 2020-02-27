<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProductDimensionsTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(VendorsTableSeeder::class);
        $this->call(CourierTableSeeder::class);

        //Mandatory
        $this->call(TransactionTypeTableSeeder::class);
        //$this->call(CourierTableSeeder::class);
    }
}
