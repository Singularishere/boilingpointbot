<?php

namespace App\ClassLibrary;

use App\Http\Models\Users;

/**
 * Class Account - Аккаунт
 * @package App\ClassLibrary
 */
class Account implements MenuInterface
{
    /**
     * @param $telegram
     * @param $telegramId
     */
    public function getResponseData($telegram, $telegramId)
    {
        $user = new Users();
        return [
            'chat_id' =>$telegramId,
            'text' => "Данные вашего аккаунта:{$this->getClientAccountFormText($user->getUserByTelegramId($telegramId))}",
        ];
    }

    /**
     * Получение данных аккаунта в формате строки
     * @param $user
     * @return string
     */
    private function getClientAccountFormText($user): string
    {
        $text = '';
        $text .= "\nИмя: {$user->name}";
        $text .= "\nEmail: {$user->email}";
        $text .= "\nTelegram id: {$user->telegramId}";
        $text .= "\nApi User_id: {$user->api_user_id}";
        $text .= "\nApi Token: {$user->apiToken}";
        $text .= "\nApi Refresh_Token: {$user->apiRefreshToken}";
        $text .= "\nCode: {$user->code}";
        $text .= "\nClient secret: {$user->client_secret}";
        $text .= "\nClient Id: {$user->client_id}";
        return $text;
    }

}
