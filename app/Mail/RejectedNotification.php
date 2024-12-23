<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $interviewer;

    public function __construct($interviewer)
    {
        $this->interviewer = $interviewer;
    }

    public function build()
    {
        return $this->subject('Interview Status: Rejected')
                    ->view('emails.rejected')
                    ->with([
                        'name' => $this->interviewer->name,
                    ]);
    }
} 