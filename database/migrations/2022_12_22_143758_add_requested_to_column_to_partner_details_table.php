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
        Schema::table('partner_details', function (Blueprint $table) {

            $table->unsignedInteger('requested_by')->comment('User who send partner make request')->nullable()->after('female_id');
            $table->unsignedInteger('requested_to_be_partner')->comment('User who is going to be a partner')->nullable()->after('requested_by');
            $table->string('requested_to_be_partner_email')->comment('Email of partner')->nullable()->after('requested_to_be_partner');
            $table->string('requested_started_by_person_qr')->nullable()->after('requested_to_be_partner_email');
            $table->boolean('is_accepted')->comment('Partner accepted requested')->nullable()->after('requested_started_by_person_qr')->default(0);

            $table->foreign('requested_by')->references('id')->on('users');
            $table->foreign('requested_to_be_partner')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner_details', function (Blueprint $table) {
            $table->dropColumn('requested_by');
            $table->dropColumn('requested_to_be_partner');
            $table->dropColumn('requested_started_by_person_qr');
            $table->dropColumn('requested_to_be_partner_email');
            $table->dropColumn('is_accepted');
        });
    }
};
