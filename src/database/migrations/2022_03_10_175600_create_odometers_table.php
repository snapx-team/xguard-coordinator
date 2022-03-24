<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOdometersTable extends Migration
{
    public function up()
    {
        Schema::create('sa_odometers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_shift_id')->constrained()->cascadeOnUpdate()->on('sa_supervisor_shifts');
            $table->unsignedMediumInteger('start_odometer');
            $table->unsignedMediumInteger('end_odometer')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sa_odometers');
    }
}
