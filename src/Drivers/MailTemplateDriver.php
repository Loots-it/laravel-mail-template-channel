<?php

namespace Lootsit\ExternalMailTemplateChannel;

interface MailTemplateDriver
{
    public function send($message);
}