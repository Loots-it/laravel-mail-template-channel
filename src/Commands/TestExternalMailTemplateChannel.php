<?php


namespace LootsIt\LaravelMailTemplateChannel\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use LootsIt\LaravelMailTemplateChannel\ExternalMailTemplateChannel;
use LootsIt\LaravelMailTemplateChannel\TestNotification;

class TestExternalMailTemplateChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailTemplateDriver:test {templateID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test your configuration of your mail template driver. No actual email is being sent.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param ExternalMailTemplateChannel $channel
     * @return void
     */
    public function handle(ExternalMailTemplateChannel $channel)
    {
        $templateID = $this->argument('templateID');

        $user = User::factory()->make();
        $notification = new TestNotification($templateID);

        if ($channel->send($user, $notification)) {
            $this->info("The configuration of the mail template driver is correct.");
        }
        else {
            $this->error("There is an error with the configuration of the mail template driver!");
        }
    }
}