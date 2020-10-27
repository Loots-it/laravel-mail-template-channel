<?php


namespace LootsIt\LaravelMailTemplateChannel\Providers;

use Illuminate\Support\ServiceProvider;
use LootsIt\LaravelMailTemplateChannel\Commands\TestExternalMailTemplateChannel;

class TestMailTemplateChannelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(TestExternalMailTemplateChannel::class);
    }
}