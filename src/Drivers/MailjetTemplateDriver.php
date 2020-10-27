<?php

namespace Lootsit\ExternalMailTemplateChannel;

use Mailjet\Client;
use Mailjet\Resources;

class MailjetTemplateDriver implements MailTemplateDriver
{

    /**
     * The Mailjet client
     *
     * @var \Mailjet\Client
     */
    protected $mj_client;


    /**
     * Create a new mailjet template driver instance.
     *
     * @return void
     */
    public function __construct()
    {
        $apikey = getenv('MAILJET_APIKEY');
        $apisecret = getenv('MAILJET_APISECRET');

        $this->mj_client = new Client($apikey, $apisecret, true, ['version' => 'v3.1']);
    }

    /**
     * Send the given message.
     *
     * @param  MailTemplateMessage  $message
     * @return void
     */
    public function send(MailTemplateMessage $message): void
    {
        $body = [
            "From" => [],
            "To" => $message->to,
            "ReplyTo" => [],
            "TemplateLanguage" => true,
            "TemplateID" => $message->templateID,
            "Variables" => $message->variables
        ];

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

        $this->mj_client->post(Resources::$Email, ['body' => $body]);
    }
}
