<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopsTable extends Migration
{

    public function up()
    {
        Schema::create('sa_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_shift_id')->constrained()->cascadeOnUpdate()->on('sa_supervisor_shifts');
            $table->date('start_time');
            $table->date('end_time');
            $table->point('location');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sa_stops');
    }
}
