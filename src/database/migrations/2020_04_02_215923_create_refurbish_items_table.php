<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefurbishItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refurbish_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('refurbish_id');
            $table->integer('product_id');
            $table->integer('qty')->unsigned();
            $table->integer('purchases_id')->nullable();
            $table->integer('rma_item_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refurbish_items');
    }
}
