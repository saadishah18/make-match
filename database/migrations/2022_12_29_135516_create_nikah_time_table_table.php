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
        Schema::create('nikah_time_table', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('imam_id');
            $table->longText('on_days')->nullable();
            $table->longText('off_days')->nullable();
            $table->longText('on_dates')->nullable();
            $table->longText('off_dates')->nullable();
            $table->longText('shift_time')->nullable();
            $table->longText('dates_of_imam')->nullable();
            $table->longText('available_on_dates')->nullable();
            $table->longText('defined_slots')->nullable();
            $table->foreign('imam_id')->references('id')->on('users');
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
        Schema::dropIfExists('nikah_time_table');
    }
};
