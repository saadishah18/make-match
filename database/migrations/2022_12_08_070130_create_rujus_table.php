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
        Schema::create('rujus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('male_id')->comment('who applied for ruju');
            $table->unsignedInteger('partner_id')->comment('associated with applied person');
            $table->unsignedInteger('nikah_id')->comment('on which nikah ruju applied');
            $table->unsignedInteger('talaq_id')->comment('on which talaq ruju applied');
            $table->boolean('otp_verified')->nullable();
            $table->dateTime('1st_ruju_applied_date')->nullable();
            $table->dateTime('2nd_ruju_applied_date')->nullable();
            $table->string('first_ruju_status')->nullable();
            $table->string('second_ruju_status')->nullable();
            $table->integer('ruju_counter');
            $table->timestamps();
            $table->foreign('partner_id')->references('id')->on('users');
            $table->foreign('male_id')->references('id')->on('users');
            $table->foreign('nikah_id')->references('id')->on('nikahs');
            $table->foreign('talaq_id')->references('id')->on('talaqs');

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
        Schema::dropIfExists('rujus');
    }
};
