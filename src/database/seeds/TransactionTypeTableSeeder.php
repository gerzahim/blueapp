<?php

use Illuminate\Database\Seeder;

class TransactionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_types')->insert([
            'id'=> 1,
            'name'=> 'PO',
            'type'=> 'in'
        ]);
        DB::table('transaction_types')->insert([
            'id'=> 2,
            'name'=> 'RMA',
            'type'=> 'out'
        ]);
        DB::table('transaction_types')->insert([
            'id'=> 3,
            'name'=> 'Return Borrowed',
            'type'=> 'in'
        ]);
        DB::table('transaction_types')->insert([
            'id'=> 4,
            'name'=> 'Sales',
            'type'=> 'out'
        ]);
        DB::table('transaction_types')->insert([
            'id'=> 5,
            'name'=> 'Lend',
            'type'=> 'out'
        ]);
        DB::table('transaction_types')->insert([
            'id'=> 6,
            'name'=> 'Refurbishment',
            'type'=> 'in'
        ]);
    }
}
