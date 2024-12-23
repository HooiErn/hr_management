<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Notifications\InterviewScheduled;
use Twilio\Rest\Client;

class WhatsAppChannel
{
    public function send($notifiable, InterviewScheduled $notification)
    {
        $message = $notification->toWhatsApp($notifiable);
        $twillioWhatsappNumber = 'whatsapp:'.env('TWILTO_WHATSAPP_FROM');
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        $twilio->messages->create(
            'whatsapp:' . $notifiable->phone_number, // Make sure phone_number is in E.164 format
            [
                'from' => env('TWILIO_WHATSAPP_FROM'),
                'body' => $message
            ]
        );
    }
} 