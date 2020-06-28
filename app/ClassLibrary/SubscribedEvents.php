<?php

namespace App\ClassLibrary;

/**
 * Class Subscribed events - События на которые подписан клиент
 * @package App\ClassLibrary
 */
class SubscribedEvents implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => 'Чтобы получить мероприятия на которые вы подписаны введите команду /GetSubscribedEvents',
        ];
    }

}
