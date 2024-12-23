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

    public function __construct($interviewData)
    {
        $this->interviewData = $interviewData;
    }

    public function via($notifiable)
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail($notifiable)
    {
        $type = $this->interviewData['interview_type'] === 'f2f' ? 'Face-to-Face' : 'Online';
        
        return (new MailMessage)
            ->subject('Interview Scheduled')
            ->greeting('Hello ' . $this->interviewData['name'])
            ->line('Your interview has been scheduled.')
            ->line('Details:')
            ->line('Date and Time: ' . $this->interviewData['interview_datetime'])
            ->line('Interview Type: ' . $type)
            ->line('Please make sure to be available at the scheduled time.')
            ->line('Thank you for your time!');
    }

    public function toWhatsApp($notifiable)
    {
        $type = $this->interviewData['interview_type'] === 'f2f' ? 'Face-to-Face' : 'Online';

        return "Hello {$this->interviewData['name']}, your interview is scheduled on {$this->interviewData['interview_datetime']} as a {$type} interview. Please be available.";
    }
}
