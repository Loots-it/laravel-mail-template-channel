<?php

declare(strict_types=1);

namespace Lootsit\ExternalMailTemplateChannel;


class MailTemplateMessage
{
    public int $templateID;
    public array $variables;

    public ?string $subject = null;

    public ?string $fromEmail = null;
    public ?string $fromName = null;

    public array $to = [];

    public ?string $replyToEmail = null;
    public ?string $replyToName = null;

    public function __construct(int $templateID, array $variables)
    {
        $this->templateID = $templateID;
        $this->variables = $variables;
    }
}