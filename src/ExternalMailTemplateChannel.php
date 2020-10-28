<?php

namespace LootsIt\LaravelMailTemplateChannel;

use Illuminate\Notifications\Notification;
use Lootsit\LaravelMailTemplateChannel\Drivers\MailTemplateDriver;

class ExternalMailTemplateChannel
{
    protected MailTemplateDriver $template_mailer;

    private string $standard_from_email;
    private string $standard_from_name;

    public function __construct(MailTemplateDriver $template_mailer)
    {
        $this->template_mailer = $template_mailer;

        $this->standard_from_email = config('external_template_mail.from.email');
        $this->standard_from_name = config('external_template_mail.from.name');
    }

    public function send($notifiable, Notification $notification): bool {
        $message = $notification->toExternalMailTemplate($notifiable);
        $message = $this->extendMessage($notifiable, $notification, $message);
        return $this->template_mailer->send($message);
    }

    protected function extendMessage($notifiable, Notification $notification, MailTemplateMessage $message): MailTemplateMessage {
        if (!$message->fromEmail and !$message->fromName) {
            $message->fromEmail = $this->standard_from_email;
            $message->fromName = $this->standard_from_name;
        }
        if (!$message->replyToEmail and !$message->replyToName) {
            $message->replyToEmail = $this->standard_from_email;
            $message->replyToName = $this->standard_from_name;
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