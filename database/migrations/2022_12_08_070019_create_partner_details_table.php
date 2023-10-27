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
        Schema::create('partner_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('male_id')->nullable();
            $table->unsignedInteger('female_id')->nullable();
            $table->unsignedInteger('nikah_id')->nullable();
            $table->boolean('is_invitation_accepted')->default(0);

            $table->foreign('male_id')->references('id')->on('users');
            $table->foreign('female_id')->references('id')->on('users');
            $table->foreign('nikah_id')->references('id')->on('nikahs');

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
        Schema::dropIfExists('partner_details');
    }
};
