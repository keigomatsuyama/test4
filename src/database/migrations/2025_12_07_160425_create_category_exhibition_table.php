<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryExhibitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('category_exhibition', function (Blueprint $table) {
            $table->id();

            // exhibitions テーブルへの FK
            $table->unsignedBigInteger('exhibition_id');

            // categories テーブルへの FK
            $table->unsignedBigInteger('category_id');

            $table->timestamps();

            // 外部キー制約
            $table->foreign('exhibition_id')
                ->references('id')->on('exhibitions')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_exhibition');
    }
}
