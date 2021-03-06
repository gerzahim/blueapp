<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->integer('contact_type_id');
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->string('tracking')->nullable();
            $table->integer('transaction_type_id');
            $table->string('bol')->nullable();
            $table->string('package_list')->nullable();
            $table->date('date')->nullable();
            $table->string('reference')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}


