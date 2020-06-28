<?php

namespace App\ClassLibrary;

/**
 * Class GetEventById - Выбрать мероприятие по id
 * @package App\ClassLibrary
 */
class GetEventById implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => 'Чтобы получить информацию о мероприятии по id мероприятия введите команду /GetEventById : id мероприятия',
        ];
    }

}

