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
        Schema::table('nikahs', function (Blueprint $table) {
            $table->boolean('is_validated')->default(0)->after('zoom_meeting_response');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nikahs', function (Blueprint $table) {
            $table->dropColumn('is_validated');

        });
    }
};
