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
        Schema::create('service_obtaineds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nikah_id');
            $table->unsignedInteger('service_id');

            $table->foreign('nikah_id')->references('id')->on('nikahs');
            $table->foreign('service_id')->references('id')->on('services');
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
        Schema::dropIfExists('service_obtaineds');
    }
};
