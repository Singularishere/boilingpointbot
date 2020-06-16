<?php

namespace App\Telegram;

use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class Events.
 */
class EventsCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'Мероприятия';

    /**
     * @var string Command Description
     */
    protected $description = 'Мероприятия в которых вы принимаете участие';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $keyboard = [
            ['Выбор перечня мероприятий в график', 'Добавление заявки по id мероприятия'],
            ['Перечень мероприятий по дате на который вы подписаны', 'Выбор конкретной площадки по ID'],
            ['Вернуться в главное меню']
        ];

        $reply_markup = $telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);

        $response = $telegram->sendMessage([
            'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
            'text' => 'Мероприятия: ',
            'reply_markup' => $reply_markup
        ]);


        $messageId = $response->getMessageId();
    }
}
