<?php

declare(strict_types=1);

namespace LootsIt\LaravelMailTemplateChannel;


class MailTemplateMessage
{
    public int $templateID;
    public array $variables;

    public ?string $subject = null;

    public ?string $fromEmail = null;
    public ?string $fromName = null;

    public ?array $to = null;

    public ?string $replyToEmail = null;
    public ?string $replyToName = null;

    public bool $testMessage = false;

    public function __construct(int $templateID, array $variables)
    {
        $this->templateID = $templateID;
        $this->variables = $variables;
    }
}