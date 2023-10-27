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
        Schema::create('pregnancy_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nikah_id');
            $table->unsignedInteger('talaq_id');
            $table->unsignedInteger('male_id');
            $table->unsignedInteger('female_id');

            $table->foreign('nikah_id')->references('id')->on('nikahs');
            $table->foreign('talaq_id')->references('id')->on('talaqs');
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
        Schema::dropIfExists('pregnancy_details');
    }
};
