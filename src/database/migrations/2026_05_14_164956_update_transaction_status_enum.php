<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTransactionStatusEnum extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM(
                'trading',
                'buyer_completed',
                'completed'
            )
            DEFAULT 'trading'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY status ENUM(
                'trading',
                'completed'
            )
            DEFAULT 'trading'
        ");
    }
}