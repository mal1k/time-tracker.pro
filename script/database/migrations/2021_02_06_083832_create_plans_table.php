<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('duration');
            $table->double('price');
            $table->double('storage_size')->nullable();
            $table->integer('project_limit');
            $table->integer('user_limit');
            $table->integer('group_limit')->default(0);
            $table->integer('gps')->default(0);
           
            $table->integer('mail_activity')->default(0);
            $table->integer('adminable_project')->default(0);
            $table->integer('screenshot')->default(0);
            $table->integer('is_featured')->default(0);
            $table->integer('status')->default(0);
            $table->integer('is_trial')->default(0);
            $table->integer('is_default')->default(0);
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
        Schema::dropIfExists('plans');
    }
}
