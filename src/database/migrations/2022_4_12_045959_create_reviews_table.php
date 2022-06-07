<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('sa_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_shift_id')->constrained()->cascadeOnUpdate();
            $table->integer('rating')->default(0);
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sa_reviews');
    }
}
