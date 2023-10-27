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
            $table->dropColumn('is_accepted');
            $table->dropColumn('is_declined');
            $table->string('first_khulu_status')
                ->default('requested')->after('khulu_counter');
            $table->string('second_khulu_status')
                ->nullable()->after('first_khulu_status');
            $table->string('second_khulu_reason')
                ->nullable()->after('reason');
            $table->text('second_khulu_detail')
                ->nullable()->after('second_khulu_reason');
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
            $table->tinyInteger('is_accepted')->nullable();
            $table->tinyInteger('is_declined')->nullable();
            $table->dropColumn('first_khulu_status');
            $table->dropColumn('second_khulu_status');
            $table->dropColumn('second_khulu_reason');
            $table->dropColumn('second_khulu_detail');
        });
    }
};
