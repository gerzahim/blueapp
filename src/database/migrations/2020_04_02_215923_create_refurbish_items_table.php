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
            $table->unsignedBigInteger('refurbish_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty')->unsigned();
            $table->unsignedBigInteger('purchases_id')->nullable();
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
