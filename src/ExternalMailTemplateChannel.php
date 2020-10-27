<?php

namespace Lootsit\ExternalMailTemplateChannel;

use Illuminate\Notifications\Notification;

class ExternalMailTemplateChannel
{
    protected $template_mailer;

    public function __construct(MailTemplateDriver $template_mailer)
    {
        $this->template_mailer = $template_mailer;
    }

    public function send($notifiable, Notification $notification) {
        $message = $notification->toExternalMailTemplate($notifiable);
        $message = $this->extendMessage($notifiable, $notification, $message);
        $this->template_mailer->send($message);
    }

    protected function extendMessage($notifiable, Notification $notification, MailTemplateMessage $message): MailTemplateMessage {
        if (!$message->fromEmail and !$message->fromName) {
            $message->fromEmail = getenv('MAILJET_FROM_EMAILADDRESS');
            $message->fromName = getenv('MAILJET_FROM_NAME');
        }
        if (!$message->replyToEmail and !$message->replyToName) {
            $message->replyToEmail = getenv('MAILJET_FROM_EMAILADDRESS');
            $message->replyToName = getenv('MAILJET_FROM_NAME');
        }
        $message->to ??= $this->getRecipients($notifiable, $notification);

        return $message;
    }

    /**
     * Get the recipients of the given message.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return mixed
     */
    protected function getRecipients($notifiable, $notification): array
    {
        if (is_string($recipients = $notifiable->routeNotificationFor('mail', $notification))) {
            $recipients = [$recipients];
        }

        $recipients = collect($recipients)->mapWithKeys(function ($recipient, $email) {
            return is_numeric($email)
                ? [$email => (is_string($recipient) ? $recipient : $recipient->email)]
                : [$email => $recipient];
        })->all();

        $mailjet_recipients = [];

        foreach ($recipients as $email => $recipient) {
            if (is_numeric($email)) {
                $mailjet_recipients[] = ["Email" => (is_string($recipient) ? $recipient : $recipient->email)];
            } else {
                $mailjet_recipients[] = [
                    "Email" => $email,
                    "Name" => $recipient
                ];
            }
        }

        return $mailjet_recipients;
    }
}