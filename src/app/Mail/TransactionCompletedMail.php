<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $buyer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item, $buyer)
    {
        $this->item = $item;
        $this->buyer = $buyer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('【取引完了】' . $this->item->name)
                    ->markdown('emails.transaction.completed');
    }
}
