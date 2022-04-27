<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationColumnsToSupervisorShiftsTable extends Migration
{
    public function up()
    {
        Schema::table('sa_supervisor_shifts', function (Blueprint $table) {
            $table->double('start_lat')->nullable()->after('end_time');;
            $table->double('start_lng')->nullable()->after('end_time');;
            $table->double('end_lat')->nullable()->after('end_time');;
            $table->double('end_lng')->nullable()->after('end_time');;
        });
    }

    public function down()
    {
        Schema::table('sa_job_site_visits', function (Blueprint $table) {
            $table->dropColumn('start_lat');
            $table->dropColumn('start_lng');
            $table->dropColumn('end_lat');
            $table->dropColumn('end_lng');
        });
    }
}
