<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOdometerPhotosTable extends Migration
{

    public function up()
    {
        Schema::create('sa_odometer_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_shift_id')->constrained()->cascadeOnUpdate()->on('sa_supervisor_shifts');
            $table->string('start_odometer_photo_path');
            $table->string('end_odometer_photo_path');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sa_odometer_photos');
    }
}
