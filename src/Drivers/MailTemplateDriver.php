<?php

namespace Lootsit\ExternalMailTemplateChannel;

interface MailTemplateDriver
{
    /**
     * Send the given message.
     *
     * @param  MailTemplateMessage  $message
     * @return void
     */
    public function send(MailTemplateMessage $message): void;
}