<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRMASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rmas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('name')->nullable();
            $table->integer('contact_type_id');
            $table->unsignedBigInteger('contact_id');
            $table->integer('transaction_type_id');
            $table->unsignedBigInteger('courier_id');
            $table->string('tracking')->nullable();
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
        Schema::dropIfExists('rmas');
    }
}
