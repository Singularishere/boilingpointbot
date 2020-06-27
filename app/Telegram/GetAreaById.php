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
 * Class Get area by id.
 */
class GetAreaById extends Command
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
    protected $name = 'GetAreaById';

    /**
     * @var string Command Description
     */
    protected $description = 'Получить площадку по id';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $requestData = $telegram->getWebhookUpdates();
        $parsedMessage = explode(':', str_replace(' ', '', $requestData['message']['text']));
        if (isset($parsedMessage[1])) {
            $areas = $this->getArea($this->user->getUserByTelegramId($requestData['message']['from']['id']),(int)$parsedMessage[1]);
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => !empty($areas) || isset($areas['error']) ? $areas['error'] : "$areas->CountryName , $areas->CityName",
            ]);
        } else {
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => 'Для того чтобы выбрать конкретную площадку по id выполните следующую команду: /GetAreaById : id площадки',
            ]);

        }
    }
}
