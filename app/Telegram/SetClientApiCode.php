<?php

namespace App\Telegram;

use App\Http\Models\Users;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
use function GuzzleHttp\Psr7\str;

/**
 * Class Set client api code.
 */
class SetClientApiCode extends Command
{
    /**
     * @var Users
     */
    private $user;

    public function __construct()
    {
        $this->user = new Users();
    }

    /**
     * @var string Command Name
     */
    protected $name = 'SetClientCode';

    /**
     * @var string Command Description
     */
    protected $description = 'Установить code';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $requestData = $telegram->getWebhookUpdates();
        $parsedMessage = explode(':', str_replace(' ', '', $requestData['message']['text']));
        if (isset($parsedMessage['1'])) {
            $this->user->setClientApiCode($requestData['message']['from']['id'], $parsedMessage[1]);
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => 'Code успешно привязан',
            ]);
        } else {
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => 'Для того чтобы закрепить за вашим аккаунтом code введите /SetClientCode : ваш code  ',
            ]);

        }
    }
}
