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
        Schema::table('rujus', function (Blueprint $table) {
//            $table->enum('second_ruju_status',['requested','rejected','complete'])
//                ->comment("requested,rejected,complete")->nullable()->after('status');
//            $table->renameColumn('status','first_ruju_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rujus', function (Blueprint $table) {
            $table->dropColumn('second_ruju_status');
//            $table->renameColumn('first_ruju_status','status');
        });
    }
};
