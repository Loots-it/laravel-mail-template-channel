<?php

namespace LootsIt\LaravelMailTemplateChannel\Drivers;

use LootsIt\LaravelMailTemplateChannel\MailTemplateMessage;
use Mailjet\Client;
use Mailjet\Resources;

class MailjetTemplateDriver implements MailTemplateDriver
{

    /**
     * The Mailjet client
     *
     * @var \Mailjet\Client
     */
    private $mj_client;

    /**
     * Create a new mailjet template driver instance.
     *
     * @return void
     */
    public function __construct()
    {
        $apikey = config('services.mailjet.key');
        $apisecret = config('services.mailjet.secret');

        $this->mj_client = new Client($apikey, $apisecret, true, ['version' => 'v3.1']);
    }

    /**
     * Send the given message.
     *
     * @param  MailTemplateMessage  $message
     * @return void
     */
    public function send(MailTemplateMessage $message): bool
    {
        $body = [
            "From" => [],
            "To" => $message->to,
            "ReplyTo" => [],
            "TemplateLanguage" => true,
            "TemplateID" => $message->templateID,
        ];

        if (count($message->variables) > 0) {
            $body["Variables"] = $message->variables;
        }

        if ($message->subject) {
            $body["Subject"] = $message->subject;
        }
        if ($message->fromName) {
            $body["From"]["Name"] = $message->fromName;
        }
        if ($message->fromEmail) {
            $body["From"]["Email"] = $message->fromEmail;
        }
        if ($message->replyToName) {
            $body["ReplyTo"]["Name"] = $message->replyToName;
        }
        if ($message->replyToEmail) {
            $body["ReplyTo"]["Email"] = $message->replyToEmail;
        }

        $body = ['Messages' => [$body]];

        if ($message->testMessage) {
            $body["SandboxMode"] = true;
        }

        return $this->mj_client
            ->post(Resources::$Email, ['body' => $body])
            ->success();
    }
}
