<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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
     try {
         // Generate the contract PDF
         $contractData = [
             'name' => $this->interviewer->name,
             'position' => $this->interviewer->job_title,
             'start_date' => now()->addWeek()->toFormattedDateString(),
             'salary' => number_format(3000, 2) // Default salary or get from your data
         ];

         $pdf = PDF::loadView('contract.contract', $contractData);
         \Log::info('Contract generated for: ' . $this->interviewer->name);

         // Build the email
         return $this->subject('Congratulations! You are Hired')
                    ->view('emails.hired')
                    ->with([
                        'name' => $this->interviewer->name,
                        'position' => $this->interviewer->job_title,
                        'start_date' => now()->addWeek()->toFormattedDateString(),
                    ])
                    ->attachData($pdf->output(), 'employment_contract.pdf', [
                        'mime' => 'application/pdf',
                    ]);
     } catch (\Exception $e) {
         \Log::error('Error in HiredNotification: ' . $e->getMessage());
         throw $e;
     }
 }
}