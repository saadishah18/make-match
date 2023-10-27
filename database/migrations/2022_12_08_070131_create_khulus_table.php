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
        Schema::create('khulus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('male_id')->comment('who applied for ruju');
            $table->unsignedInteger('partner_id')->comment('associated with applied person');
            $table->unsignedInteger('nikah_id')->comment('on which nikah ruju applied');
            $table->boolean('otp_verified')->nullable();
            $table->boolean('is_declined')->nullable();
            $table->boolean('is_accepted')->nullable();
            $table->dateTime('1st_khulu_applied_date')->nullable();
            $table->dateTime('2nd_khulu_applied_date')->nullable();
            $table->integer('khulu_counter');
            $table->string('reason');
            $table->text('details');
            $table->timestamps();

            $table->foreign('partner_id')->references('id')->on('users');
            $table->foreign('male_id')->references('id')->on('users');
            $table->foreign('nikah_id')->references('id')->on('nikahs');

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
        Schema::dropIfExists('khulus');
    }
};
