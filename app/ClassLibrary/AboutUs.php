<?php

namespace App\ClassLibrary;

/**
 * Class AboutUs - О нас
 * @package App\ClassLibrary
 */
class AboutUs implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => trans('messages.about_us'),
        ];
    }

}
