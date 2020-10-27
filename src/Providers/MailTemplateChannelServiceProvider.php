<?php


namespace LootsIt\LaravelMailTemplateChannel\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use LootsIt\LaravelMailTemplateChannel\ExternalMailTemplateChannel;

class MailTemplateChannelServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        ExternalMailTemplateChannel::class => ExternalMailTemplateChannel::class
    ];

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ExternalMailTemplateChannel::class
        ];
    }
}