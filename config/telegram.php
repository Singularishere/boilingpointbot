<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot API Access Token [REQUIRED]
    |--------------------------------------------------------------------------
    |
    | Your Telegram's Bot Access Token.
    | Example: 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
    |
    | Refer for more details:
    | https://core.telegram.org/bots#botfather
    |
    */
    'bot_token' => env('TELEGRAM_BOT_TOKEN', '1227806272:AAFaBHGQCAUMmclvqrd98S1O0BDPrNlwK2w'),

    /*
    |--------------------------------------------------------------------------
    | Asynchronous Requests [Optional]
    |--------------------------------------------------------------------------
    |
    | When set to True, All the requests would be made non-blocking (Async).
    |
    | Default: false
    | Possible Values: (Boolean) "true" OR "false"
    |
    */
    'async_requests' => env('TELEGRAM_ASYNC_REQUESTS', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Handler [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use a custom HTTP Client Handler.
    | Should be an instance of \Telegram\Bot\HttpClients\HttpClientInterface
    |
    | Default: GuzzlePHP
    |
    */
    'http_client_handler' => null,

    /*
    |--------------------------------------------------------------------------
    | Register Telegram Commands [Optional]
    |--------------------------------------------------------------------------
    |
    | If you'd like to use the SDK's built in command handler system,
    | You can register all the commands here.
    |
    | The command class should extend the \Telegram\Bot\Commands\Command class.
    |
    | Default: The SDK registers, a help command which when a user sends /help
    | will respond with a list of available commands and description.
    |
    */
    'commands' => [
        Telegram\Bot\Commands\HelpCommand::class,
        App\Telegram\MenuCommand::class,
        App\Telegram\EventsCommand::class,
        App\Telegram\AuthorizeCommand::class,
        App\Telegram\SetClientId::class,
        App\Telegram\SetClientSecretKey::class,
        App\Telegram\SetClientApiCode::class,
        App\Telegram\GetAreaById::class,
        App\Telegram\GetAccessToken::class,
        App\Telegram\RefreshApiToken::class,
        App\Telegram\EventsByDateCommand::class,
        App\Telegram\SubscribeEventById::class,
        App\Telegram\SubscribedEventsByDate::class,
        App\Telegram\GetEventById::class
    ],
];
