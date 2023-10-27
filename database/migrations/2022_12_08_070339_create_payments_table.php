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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('male_id');
            $table->unsignedInteger('female_id');
            $table->unsignedInteger('activity_id');
            $table->string('activity_name');
            $table->float('services_total_price');
            $table->float('vat');
            $table->float('platform_fee');
            $table->float('total');
            $table->string('paid_by_platform');
            $table->string('transaction_id');
            $table->string('status');

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
        Schema::dropIfExists('payments');
    }
};
