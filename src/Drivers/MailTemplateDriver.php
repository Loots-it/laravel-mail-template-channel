<?php

namespace Lootsit\LaravelMailTemplateChannel\Drivers;

use LootsIt\LaravelMailTemplateChannel\MailTemplateMessage;

interface MailTemplateDriver
{
    /**
     * Send the given message.
     *
     * @param  MailTemplateMessage  $message
     * @return void
     */
    public function send(MailTemplateMessage $message): bool;
}