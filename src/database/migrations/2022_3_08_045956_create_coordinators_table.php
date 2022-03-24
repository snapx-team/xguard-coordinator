<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoordinatorsTable extends Migration
{

    public function up()
    {
        Schema::create('sa_coordinators', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sa_coordinators');
    }
}
