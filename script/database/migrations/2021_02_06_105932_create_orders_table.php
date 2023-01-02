<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('getway_id');//payment getway
            $table->unsignedBigInteger('user_id');
            $table->double('amount')->nullable();
            $table->double('tax')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('invoice_id')->nullable();
            $table->integer('status')->default(3);
            $table->integer('payment_status')->default(2);
            $table->integer('is_trial')->default(0);
            $table->date('will_expire');
            $table->timestamps();
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('getway_id')->references('id')->on('getways')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
