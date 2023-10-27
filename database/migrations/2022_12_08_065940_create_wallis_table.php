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
        Schema::create('wallis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invited_by')->comment('user who invites');
            $table->unsignedInteger('user_as_wali_id')->comment('user invited as walli');
            $table->unsignedInteger('nikah_id')->comment('user invited as walli for which nikah');
            $table->foreign('invited_by')->references('id')->on('users');
            $table->foreign('user_as_wali_id')->references('id')->on('users');
            $table->boolean('is_invitation_accepted')->default(0);
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
        Schema::dropIfExists('wallis');
    }
};
