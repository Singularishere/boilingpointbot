<?php


namespace App\ClassLibrary;

/**
 * Class Authorize - Авторизация
 * @package App\ClassLibrary
 */
class Authorize implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => trans('messages.authorize_text'),
        ];
    }

}
