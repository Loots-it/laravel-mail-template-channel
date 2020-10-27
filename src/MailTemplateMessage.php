<?php

declare(strict_types=1);

namespace Lootsit\ExternalMailTemplateChannel;


class MailTemplateMessage
{
    public int $templateID;
    public array $variables;

    public string $subject;

    public string $fromEmail;
    public string $fromName;

    public array $to;

    public string $replyToEmail;
    public string $replyToName;

    public function __construct(int $templateID, array $variables)
    {
        $this->templateID = $templateID;
        $this->variables = $variables;
    }
}