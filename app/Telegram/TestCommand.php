<?php

namespace App\Telegram;

use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class TestCommand.
 */
class TestCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'test';

    /**
     * @var string Command Description
     */
    protected $description = 'Test command, Get a list of commands';

    /**
     * {@inheritdoc}
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle($arguments)
    {
        $text = '';
        $api = new Api(Telegram::getAccessToken(), true);
        $tg = $api->getWebhookUpdates();
        $user = $tg['message'];
        $text .= sprintf('%s - %s'.PHP_EOL, 'Name:',$user['from']['username']);
        $this->replyWithMessage(compact('text'));
    }
}
