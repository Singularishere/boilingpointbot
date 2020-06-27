<?php

namespace App\ClassLibrary;

use App\Telegram\MenuCommand;

/**
 * Class BackToMainMenu - Возврат к главному меню
 * @package App\ClassLibrary
 */
class BackToMainMenu implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        return [
            'chat_id' => $telegramId,
            'text' => '/start',
            'reply_markup' => MenuCommand::getMenuReplyMarkup($telegram)
        ];
    }

}
