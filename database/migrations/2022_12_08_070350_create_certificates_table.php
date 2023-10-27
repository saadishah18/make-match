<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('male_id');
            $table->unsignedInteger('female_id');
            $table->unsignedInteger('activity_id');
            $table->string('activity_name');
            $table->string('activity_model');
            $table->string('system_certificate',191);

            $table->foreign('male_id')->references('id')->on('users');
            $table->foreign('female_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificates');
    }
};
