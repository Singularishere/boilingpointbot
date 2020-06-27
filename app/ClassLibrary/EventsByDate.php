<?php

namespace App\ClassLibrary;

/**
 * Class EventsByDate - Выбор перечня мероприятий в график
 * @package App\ClassLibrary
 */
class EventsByDate implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => 'Для того чтобы выбрать перечень мероприятий в график введите команду /GetEventsByDate : дд.мм.гггг - дд.мм.гггг'
        ];
    }

}
