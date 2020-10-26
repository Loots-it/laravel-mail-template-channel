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
        $body = $this->getBody($notifiable, $notification, $message);
        $this->template_mailer->send($body);
    }

    protected function getBody($notifiable, Notification $notification, $message) {
        if (!array_key_exists('From', $message)) {
            $message['From'] = [
                'Email' => getenv('MAILJET_FROM_EMAILADDRESS'),
                'Name' => getenv('MAILJET_FROM_NAME')
            ];
        }

        if (!array_key_exists('To', $message)) {
            $message['To'] = $this->getRecipients($notifiable, $notification);;
        }

        if (!array_key_exists('ReplyTo', $message)) {
            $message['ReplyTo'] = [
                'Email' => getenv('MAILJET_FROM_EMAILADDRESS'),
                'Name' => getenv('MAILJET_FROM_NAME')
            ];
        }
        if (!array_key_exists('TemplateLanguage', $message)) {
            $message['TemplateLanguage'] = true;
        }

        return ['Messages' => [$message]];
    }

    /**
     * Get the recipients of the given message.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return mixed
     */
    protected function getRecipients($notifiable, $notification)
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