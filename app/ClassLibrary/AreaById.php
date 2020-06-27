<?php

namespace App\ClassLibrary;

/**
 * Class AreaById - Получение площадки по id
 * @package App\ClassLibrary
 */
class AreaById implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => "Для того чтобы выбрать конкретную площадку по id выполните следующую команду: /GetAreaById : id площадки",
        ];
    }

}
