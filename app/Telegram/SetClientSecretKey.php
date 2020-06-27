<?php

namespace App\Telegram;

use App\Http\Models\Users;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
use function GuzzleHttp\Psr7\str;

/**
 * Class Set secret key
 */
class SetClientSecretKey extends Command
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
    protected $name = 'SetSecretKey';

    /**
     * @var string Command Description
     */
    protected $description = 'Привязать secret_key';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $requestData = $telegram->getWebhookUpdates();
        $parsedMessage = explode(':', str_replace(' ', '', $requestData['message']['text']));
        if (isset($parsedMessage['1'])) {
            try {
                $this->user->setSecretKey($requestData['message']['from']['id'], $parsedMessage[1]);
                $telegram->sendMessage([
                    'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                    'text' => 'Secret key успешно привязан',
                ]);
            } catch (\Exception $exception) {
                $telegram->sendMessage([
                    'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                    'text' => $exception->getMessage(),
                ]);
            }
        } else {
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => 'Для того чтобы закрепить за вашим аккаунтом secret_key введите /SetSecretKey : ваш secret_key',
            ]);

        }
    }
}
