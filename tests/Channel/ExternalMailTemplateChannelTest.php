<?php

namespace LootsIt\LaravelMailTemplateChannel\Tests\Channel;


use Illuminate\Support\Facades\Config;
use Lootsit\LaravelMailTemplateChannel\Drivers\MailTemplateDriver;
use LootsIt\LaravelMailTemplateChannel\ExternalMailTemplateChannel;

use App\Models\User;
use LootsIt\LaravelMailTemplateChannel\MailTemplateMessage;
use LootsIt\LaravelMailTemplateChannel\TestNotification;
use Mockery;
use Tests\TestCase;


class ExternalMailTemplateChannelTest extends TestCase
{
    public function testChannel()
    {
        $fromEmail = 'test@test.com';
        $fromName = 'test';

        Config::set('external_template_mail.from.email', $fromEmail);
        Config::set('external_template_mail.from.name', $fromName);

        $user = User::factory()->make();
        $notification = new TestNotification(1);

        $message = new MailTemplateMessage(1, []);
        $message->subject = "Test mail";
        $message->fromEmail = $fromEmail;
        $message->fromName = $fromName;
        $message->replyToEmail = $fromEmail;
        $message->replyToName = $fromName;
        $message->to = [["Email" => $user->email]];
        $message->testMessage = true;

        $fakeDriver = Mockery::mock(MailTemplateDriver::class);
        $fakeDriver
            ->shouldReceive('send')
            ->with(Mockery::on(function($value) use ($message) {
                return $value == $message;
            }))
            ->andReturn(true);

        $channel = new ExternalMailTemplateChannel($fakeDriver);

        $this->assertTrue($channel->send($user, $notification));

        Mockery::close();
    }
}