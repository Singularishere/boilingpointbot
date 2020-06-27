<?php

namespace App\ClassLibrary;

/**
 * Class EventsById - Добавление заявки по id мероприятия
 * @package App\ClassLibrary
 */
class EventsById implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => 'Чтобы отправить заявку на мероприятие введите /SubscribeEventById : id мероприятия',
        ];
    }

}
