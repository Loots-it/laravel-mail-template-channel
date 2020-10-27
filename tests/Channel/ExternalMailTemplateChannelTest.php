<?php

namespace LootsIt\LaravelMailTemplateChannel\Tests\Channel;


use Lootsit\LaravelMailTemplateChannel\Drivers\MailTemplateDriver;
use LootsIt\LaravelMailTemplateChannel\ExternalMailTemplateChannel;

use App\Models\User;
use LootsIt\LaravelMailTemplateChannel\MailTemplateMessage;
use LootsIt\LaravelMailTemplateChannel\Tests\SimpleNotification;
use Mockery;
use Tests\TestCase;


class ExternalMailTemplateChannelTest extends TestCase
{
    public function testChannel()
    {
        $user = User::factory()->make();
        $notification = new SimpleNotification();
        $message = new MailTemplateMessage(1, []);
        $message->subject = "Verify email address";
        $message->fromEmail = getenv('MAILJET_FROM_EMAILADDRESS');
        $message->fromName = getenv('MAILJET_FROM_NAME');
        $message->replyToEmail = getenv('MAILJET_FROM_EMAILADDRESS');
        $message->replyToName = getenv('MAILJET_FROM_NAME');
        $message->to = [["Email" => $user->email]];

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