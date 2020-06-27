<?php

namespace App\ClassLibrary;

/**
 * Class Instagram - Инстаграм
 * @package App\ClassLibrary
 */
class Instagram implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => 'https://www.instagram.com/tochkak/',
        ];
    }

}
