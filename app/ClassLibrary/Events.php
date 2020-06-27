<?php

namespace App\ClassLibrary;

use App\Telegram\EventsCommand;

/**
 * Class Events - Мероприятия
 * @package App\ClassLibrary
 */
class Events implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => '/Мероприятия',
            'reply_markup' => EventsCommand::getEventsKeyboardCommands($telegram)
        ];
    }

}

