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
        Schema::create('talaqs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('male_id')->comment('who applied for nikah');
            $table->unsignedInteger('partner_id')->comment('female associated with partner in nikah');
            $table->unsignedInteger('nikah_id')->comment('aginst which nikah talaq taken');
            $table->timestamp('1st_talaq_date')->nullable();
            $table->timestamp('2nd_talaq_date')->nullable();
            $table->timestamp('3rd_talaq_date')->nullable();
            $table->integer('talaq_counter')->nullable();
            $table->boolean('is_confirmed_by_otp')->nullable();
            $table->boolean('is_ruju_applied')->nullable();

            $table->foreign('nikah_id')->references('id')->on('nikahs');
            $table->foreign('male_id')->references('id')->on('users');
            $table->foreign('partner_id')->references('id')->on('users');

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
        Schema::dropIfExists('talaqs');
    }
};
