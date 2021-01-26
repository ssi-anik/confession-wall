<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfessionsTable extends Migration
{
    public function up () {
        Schema::create('confessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receiver_id');
            $table->text('body');
            $table->unsignedBigInteger('poster_id')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down () {
        Schema::dropIfExists('confessions');
    }
}
