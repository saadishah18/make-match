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
        Schema::create('nikah_detail_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nikah_id');
            $table->unsignedInteger('male_id');
            $table->unsignedInteger('female_id');
            $table->timestamp('nikah_date');
            $table->boolean('is_cancelled')->nullable();
            $table->timestamp('cancellation_date')->nullable();
            $table->boolean('cancellation_verified')->nullable();
            $table->boolean('is_talaq_applied')->nullable();
            $table->unsignedInteger('talaq_id')->nullable();
            $table->boolean('is_ruju_applied')->nullable();
            $table->unsignedInteger('ruju_id')->nullable();
            $table->boolean('is_khulu_applied')->nullable();
            $table->unsignedInteger('khulu_id')->nullable();
            $table->string('current_status')->default('nikahfied');

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
        Schema::dropIfExists('nikah_detail_histories');
    }
};
