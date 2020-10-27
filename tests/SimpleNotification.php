<?php


namespace LootsIt\LaravelMailTemplateChannel\Tests;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use LootsIt\LaravelMailTemplateChannel\ExternalMailTemplateChannel;
use LootsIt\LaravelMailTemplateChannel\MailTemplateMessage;

class SimpleNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [ExternalMailTemplateChannel::class];
    }

    public function toExternalMailTemplate($notifiable) {
        $message = new MailTemplateMessage(1, []);
        $message->subject = "Verify email address";

        return $message;
    }
}