<?php

namespace App\Telegram;

use App\Http\Models\Users;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
use function GuzzleHttp\Psr7\str;
use App\Http\Traits;

/**
 * Class Subscribe Event By Id.
 */
class SubscribeEventById extends Command
{
    use Traits\Api;

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
    protected $name = 'SubscribeEventById';

    /**
     * @var string Command Description
     */
    protected $description = 'Добавление заявки на мероприятие по id мероприятия';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $requestData = $telegram->getWebhookUpdates();
        $requestText = explode(':', str_replace(' ', '', $requestData['message']['text']));
        if (isset($requestText[1])) {
            $user = $this->user->getUserByTelegramId($requestData['message']['from']['id']);
            $registerEvent = $this->registerEvent((int)$requestText[1], $user);
            $telegram->sendMessage([
                'chat_id' => $requestData['message']['from']['id'],
                'text' => !empty($registerEvent) && $registerEvent instanceof \Exception ? $registerEvent->getMessage() : $registerEvent,
            ]);
        } else {
            $telegram->sendMessage([
                'chat_id' => $requestData['message']['from']['id'],
                'text' => 'Чтобы отправить заявку на мероприятие введите /SubscribeEventById : id мероприятия'
            ]);
        }

    }
}
