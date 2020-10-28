<?php

namespace LootsIt\LaravelMailTemplateChannel\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use LootsIt\LaravelMailTemplateChannel\Drivers\MailjetTemplateDriver;
use Lootsit\LaravelMailTemplateChannel\Drivers\MailTemplateDriver;

class MailTemplateDriverServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Drivers/external_template_mail.php' => config_path('external_template_mail.php'),
        ]);
    }

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