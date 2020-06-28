<?php

namespace App\Telegram;

use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class AuthorizeCommand.
 */
class AuthorizeCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'Authorize';

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
        $api = new Api(Telegram::getAccessToken(), true);
        $tg = $api->getWebhookUpdates();
        if ($tg['message']['text'] == '/Authorize') {
            $api->sendMessage([
                'chat_id' => $tg['message']['from']['id'],
                'text' => "Авторизация необходима, для использования api leader-id.\nДля того чтобы авторизоваться вам необходимо ввести code,client_id,secret_key от вашего аккаунта в leader-id\r\nКоманды для привязки:\n/SetClientId - для привязки client_id\n/SetSecretKey - для привязки secret_key\n/SetClientCode - для привязки code\n",
            ]);
        } else {
            $api->sendMessage([
                'chat_id' => $tg['message']['from']['id'],
                'text' => $tg['message']['text'],
            ]);
        }
    }
}
