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
        Schema::table('khulus', function (Blueprint $table) {
            $table->string('payment_status')->default('in-complete');
            $table->string('request_data')->nullable();
            $table->string('payment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('khulus', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('request_data');
            $table->dropColumn('payment_id');
        });
    }
};
