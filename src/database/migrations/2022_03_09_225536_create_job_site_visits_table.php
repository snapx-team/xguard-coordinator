<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobSiteVisitsTable extends Migration
{

    public function up()
    {
        Schema::create('sa_job_site_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_site_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('supervisor_shift_id')->constrained()->cascadeOnUpdate()->on('sa_supervisor_shifts');
            $table->date('start_time');
            $table->date('end_time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sa_job_site_visits');
    }
}
