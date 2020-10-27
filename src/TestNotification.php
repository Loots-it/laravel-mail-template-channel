<?php


namespace LootsIt\LaravelMailTemplateChannel;


use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    private int $templateID;

    public function __construct(int $templateID)
    {
        $this->templateID = $templateID;
    }

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
        $message = new MailTemplateMessage($this->templateID, []);
        $message->subject = "Test mail";
        $message->testMessage = true;

        return $message;
    }
}