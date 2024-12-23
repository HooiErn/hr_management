<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HiredNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $interviewer;

    public function __construct($interviewer)
    {
        $this->interviewer = $interviewer;
    }

 // In the HiredNotification Mailable
    public function build()
    {
        $start_date = now()->addWeek()->toFormattedDateString(); // Adds 1 week to the current date

        return $this->subject('Congratulations! You are Hired')
                    ->view('emails.hired')
                    ->with([
                        'name' => $this->interviewer->name,
                        'interview_datetime' => $this->interviewer->interview_datetime,
                        'start_date' => $start_date,  // Passing the calculated start date
                    ]);
    }

} 