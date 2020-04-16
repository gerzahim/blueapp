<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchases_id');
            $table->unsignedBigInteger('purchases_item_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('purchased')->unsigned();
            $table->integer('sold')->unsigned()->nullable();
            $table->integer('qoh')->unsigned()->nullable();
            $table->integer('on_hold')->unsigned()->nullable();
            $table->integer('available')->unsigned()->nullable();
            $table->integer('rma')->unsigned()->nullable();
            $table->integer('refurbished')->unsigned()->nullable();
            $table->integer('lended')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     *
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
