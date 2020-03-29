<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRMAItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rma_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rma_id');
            $table->integer('product_id');
            $table->integer('qty')->unsigned();
            $table->integer('order_id')->nullable();
            $table->integer('purchases_id')->nullable();
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
        Schema::dropIfExists('rma_items');
    }
}
