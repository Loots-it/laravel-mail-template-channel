<?php

namespace LootsIt\LaravelMailTemplateChannel\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use LootsIt\LaravelMailTemplateChannel\Drivers\MailjetTemplateDriver;
use Lootsit\LaravelMailTemplateChannel\Drivers\MailTemplateDriver;

class MailTemplateDriverServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        MailTemplateDriver::class => MailjetTemplateDriver::class
    ];

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [MailTemplateDriver::class];
    }
}