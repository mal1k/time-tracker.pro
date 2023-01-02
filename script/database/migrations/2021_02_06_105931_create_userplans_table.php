<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userplans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->double('storage_size')->nullable();
            $table->integer('project_limit')->default(0);
            $table->integer('user_limit')->default(0);
            $table->integer('group_limit')->default(0);
            $table->integer('gps')->default(0);
            $table->integer('mail_activity')->default(0);
            $table->integer('adminable_project')->default(0);   
            $table->integer('screenshot')->default(0);
            $table->integer('is_featured')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userplans');
    }
}
