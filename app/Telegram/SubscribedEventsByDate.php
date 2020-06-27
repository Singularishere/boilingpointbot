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
class SubscribedEventsByDate extends Command
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
    protected $name = 'GetSubscribedEvents';

    /**
     * @var string Command Description
     */
    protected $description = 'Получить перечень мероприятий по дате на который вы подписаны';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $requestData = $telegram->getWebhookUpdates();
        $apiData = $this->getUserEventsList($this->user->getUserByTelegramId($requestData['message']['from']['id']));
        if (isset($apiData['error'])) {
            $telegram->sendMessage([
                'chat_id' => $requestData['message']['from']['id'],
                'text' => $apiData['error'],
            ]);
        } else {
            foreach ($apiData as $item) {
                $telegram->sendMessage([
                    'chat_id' => $requestData['message']['from']['id'],
                    'text' => "Id мероприятия: $item->Id\r\nНазвание мероприятия: $item->Name\r\nИнформация о мероприятии: $item->Info",
                ]);
            }
        }
    }
}
