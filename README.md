## Installation

First, include the package with composer:

```
composer require loots-it/laravel-mail-template-channel
```

The most important Provider is the MailTemplateDriverServiceProvider and it's auto discovered via the composer.json
file.

The other Provider is TestMailTemplateChannelServiceProvider which adds an Artisan command to test your
MailTemplateDriver configuration. If you want to use it, you will need to add it manually to **config/app.php**.

* The providers array:
```php
'providers' => [
    ...
    LootsIt\LaravelMailTemplateChannel\Providers\TestMailTemplateChannelServiceProvider::class,
    LootsIt\LaravelMailTemplateChannel\Providers\MailTemplateDriverServiceProvider::class, // This one is auto discovered
    ...
],
```

## Configuration

Add the Mailjet API key/secret in your **.env** file:

```php
MAILJET_APIKEY=YOUR_APIKEY
MAILJET_APISECRET=YOUR_APISECRET
```

Your API key / secret can be found [here](https://app.mailjet.com/account/api_keys).

Add the following section to the **config/services.php** file:

```php
'mailjet' => [
    'key' => env('MAILJET_APIKEY'),
    'secret' => env('MAILJET_APISECRET'),
],
```

If you want to send emails via notifications without specifying the sender email/name in the notification, you will have
to add a default sender. To do this, you will first have to publish the config file:

```
php artisan vendor:publish --provider="LootsIt\LaravelMailTemplateChannel\Providers\MailTemplateDriverServiceProvider"
```

Then you have to add a default sender email address and default sender name:

```php
return [
    'from' => [
        'email' => 'info@yourdomain.com',
        'name' => 'yourdomain.com',
    ],
];
```

## Test configuration

You can test your configuration of the mail template Driver/Channel using the command provided by the TestMailTemplateChannelServiceProvider. You will need
a template that doesn't need any variables and pass the id (in this example 1) to the command:

```
php artisan mailTemplateDriver:test 1
```

This won't actually send an email, it will only test the configuration.

## Usage

You can use this Channel for any Notification. As an example, I will create a minimal Notification here:

```
php artisan make:notification VerifyEmailNotification
```

You can delete the standard **toMail($notifiable)** method if you want. You should change the via method and implement
the **toExternalMailTemplate($notifiable)** like below:

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use LootsIt\LaravelMailTemplateChannel\ExternalMailTemplateChannel;
use LootsIt\LaravelMailTemplateChannel\MailTemplateMessage;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    private int $templateID;
    private string $verificationLink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($templateID, $verificationLink)
    {
        $this->templateID = $templateID;
        $this->verificationLink = $verificationLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ExternalMailTemplateChannel::class];
    }

    public function toExternalMailTemplate($notifiable) {

        $variables = [
            "verification_link" => $this->verificationLink,
        ];

        $message = new MailTemplateMessage($this->templateID, $variables);
        $message->subject = "Verify email address";

        return $message;
    }
}
```

You can use this notification like this:

````php
$user->notify(New VerifyEmailNotification($templateID, $verificationLink));
````

