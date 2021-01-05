<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up () {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('username', 30)->index();
            $table->string('email', 80);
            $table->string('password', 72);
            // TODO: Implement mailing and verification
            // $table->dateTime('verified_at')->nullable();
            $table->unsignedTinyInteger('is_banned')->default(0);
            $table->unsignedTinyInteger('message_from')->default(User::MSG_FROM_REGISTERED_USER);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down () {
        Schema::dropIfExists('users');
    }
}
