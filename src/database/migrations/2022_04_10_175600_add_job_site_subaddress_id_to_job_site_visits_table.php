<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobSiteSubAddressIdToJobSiteVisitsTable extends Migration
{
    public function up()
    {
        Schema::table('sa_job_site_visits', function (Blueprint $table) {
            $table->unsignedBigInteger('job_site_subaddress_id')->nullable()->after('end_time');
        });
    }

    public function down()
    {
        Schema::table('sa_job_site_visits', function (Blueprint $table) {
            $table->dropColumn('job_site_subaddress_id');
        });
    }
}
