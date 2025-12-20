<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->string('profile_image')->nullable();  // jpeg / png
            $table->string('username', 20);               // ユーザー名
            $table->string('postal_code', 8);             // 123-4567
            $table->string('address');                    // 住所
            $table->string('building')->nullable();

            $table->timestamps(); // ← created_at / updated_at はこれだけでOK

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
