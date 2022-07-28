<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationPingsTable extends Migration
{

    public function up()
    {
        Schema::create('sa_location_pings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_shift_id')->constrained()->cascadeOnUpdate()->on('sa_supervisor_shifts');
            $table->double('lat');
            $table->double('lng');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sa_location_pings');
    }
}
