<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade as PDF;

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
     // Generate the contract PDF
     $contractData = [
         'name' => $this->interviewer->name,
         'position' => $this->interviewer->position,  // Add this property as needed
         'start_date' => now()->addWeek()->toFormattedDateString(),
         'salary' => $this->interviewer->salary,  // Add this property as needed
     ];
     $pdf = PDF::loadView('contracts.contract', $contractData);
 
     // Build the email and attach the contract PDF
     return $this->subject('Congratulations! You are Hired')
                 ->view('emails.hired')
                 ->with([
                     'name' => $this->interviewer->name,
                     'interview_datetime' => $this->interviewer->interview_datetime,
                     'start_date' => now()->addWeek()->toFormattedDateString(),
                 ])
                 ->attachData($pdf->output(), 'contract.pdf', [
                     'mime' => 'application/pdf',
                 ]);
 }
}