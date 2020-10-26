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
     * Send the given body.
     *
     * @param  mixed  $body
     * @return void
     */
    public function send($body)
    {
        $this->mj_client->post(Resources::$Email, ['body' => $body]);
    }
}
