<?php

namespace App\Telegram;

use App\Http\Models\Users;
use App\User;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use App\Http\Traits;
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class EventsByDateCommand.
 */
class EventsByDateCommand extends Command
{
    use Traits\Api;

    /**
     * @var string Command Name
     */
    protected $name = 'GetEventsByDate';

    /**
     * @var string Command Description
     */
    protected $description = 'Выбор перечня мероприятий в график';

    /**
     * {@inheritdoc}
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken(), true);
        $requestData = $telegram->getWebhookUpdates();
        $requestText = str_replace(' ', '', $requestData['message']['text']);
        $requestText = explode(':', $requestText);
        if (isset($requestText[1])) {
            $date = explode('-', $requestText[1]);
            $user = new Users();
            $user = $user->getUserByTelegramId($requestData['message']['from']['id']);
            $events = $this->getEventsByDate($date, $user);
            foreach ($events as $event) {
                $telegram->sendMessage([
                    'chat_id' => $requestData['message']['from']['id'],
                    'text' => "Название:  {$event['Name']}\r\nИнформация:  {$event['Info']}\r\nUrl:   {$event['Url']}",
                ]);
            }
        }
    }
}
