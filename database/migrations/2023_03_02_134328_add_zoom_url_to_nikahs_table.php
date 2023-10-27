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
            $table->string('zoom_start_url',255)->nullable()->after('imam_id');
            $table->string('zoom_join_url',255)->nullable()->after('zoom_start_url');
            $table->string('zoom_host_id',255)->nullable()->after('zoom_join_url');
            $table->json('zoom_meeting_response')->nullable()->after('zoom_host_id');
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
            $table->dropColumn('zoom_start_url');
            $table->dropColumn('zoom_join_url');
            $table->dropColumn('zoom_host_id');
            $table->dropColumn('zoom_meeting_response');
        });
    }
};
