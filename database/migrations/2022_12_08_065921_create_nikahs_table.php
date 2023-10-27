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
        Schema::create('nikahs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nikah_type_id')->comment('nikah type id');
            $table->unsignedInteger('user_id')->comment('who applied for nikah');
            $table->unsignedInteger('partner_id')->comment('with person nikah applied')->nullable();
            $table->date('nikah_date');
            $table->timestamp('nikah_date_time');

            $table->foreign('nikah_type_id')->references('id')->on('nikah_types');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('nikahs');
    }
};
