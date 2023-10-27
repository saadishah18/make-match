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
        Schema::create('application_activity_detail_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('activity_performer_id')->comment('which user do action');
            $table->string('activity_detail');
            $table->unsignedInteger('activity_entity_id');
            $table->unsignedInteger('activity_model');
            $table->string('extra_detail');
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
        Schema::dropIfExists('application_activity_detail_logs');
    }
};
