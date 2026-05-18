<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('item_id')
                ->constrained('exhibitions');

            $table->foreignId('seller_id')
                ->constrained('users');

            $table->foreignId('buyer_id')
                ->constrained('users');
            $table->enum('status', [
                'trading',
                'buyer_completed',
                'completed'
            ])->default('trading');

            $table->integer('buyer_rating')
                ->nullable();

            $table->integer('seller_rating')
                ->nullable();
            $table->integer('unread_count')
                ->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
