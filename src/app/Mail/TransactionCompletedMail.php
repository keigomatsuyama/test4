<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;

    public $partner;

    public function __construct(
        $transaction,
        $partner
    ) {
        $this->transaction = $transaction;

        $this->partner = $partner;
    }

    public function build()
    {
        return $this
            ->subject('取引完了のお知らせ')
            ->view('emails.transaction_completed');
    }
}