<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Channels\WhatsAppChannel;

class InterviewScheduled extends Notification
{
    use Queueable;

    protected $interviewData;
    protected $roomID;

    public function __construct($interviewData, $roomID = null)
    {
        $this->interviewData = $interviewData;
        $this->roomID = $roomID;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $type = $this->interviewData['interview_type'] === 'f2f' ? 'Face-to-Face' : 'Online';
    
        // Start with the usual details
        $mailMessage = (new MailMessage)
            ->subject('Interview Scheduled')
            ->greeting('Hello ' . $this->interviewData['name'])
            ->line('Your interview has been scheduled.')
            ->line('Details:')
            ->line('Date and Time: ' . $this->interviewData['interview_datetime'])
            ->line('Interview Type: ' . $type);
    
        // Add conditional logic 
        if ($this->interviewData['interview_type'] === 'online') {
            $mailMessage->line('Room ID: ' . $this->roomID) // Show Room ID for online interviews
                        ->line('Please use this Room ID to join the interview online.')
                        ->line('Link: http://localhost:8000/public/meeting');
        } else {
            // For face-to-face interviews, mention the location instead
            $mailMessage->line('Please come to the company location for your Face-to-Face interview.')
                        ->line('Please bring the following documents with you for the interview:')
                        ->line('- A copy of your IC (Identity Card), both front and back.')
                        ->line('- A copy of your highest educational certificate.\n')
                        ->line('- Any relevant work experience certificates (if applicable).\n')
                        ->line('- Proof of previous employment (if applicable).')
                        ->line('- Any other personal identification documents required by the company.');
        }
    
        // Common message
        $mailMessage->line('Please make sure to be available at the scheduled time.')
                    ->line('Thank you for your time!');
    
        return $mailMessage;
    }
    

    public function toWhatsApp($notifiable)
    {
        $type = $this->interviewData['interview_type'] === 'f2f' ? 'Face-to-Face' : 'Online';

        return "Hello {$this->interviewData['name']}, your interview is scheduled on {$this->interviewData['interview_datetime']} as a {$type} interview with Room ID: {$this->roomID}. Please be available.";
    }
}

