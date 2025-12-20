<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exhibition_id'); // 商品IDを保存
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exhibition_id')->references('id')->on('exhibitions')->onDelete('cascade');

            $table->unique(['user_id', 'exhibition_id']); // 同じユーザーの重複いいねを防止
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
