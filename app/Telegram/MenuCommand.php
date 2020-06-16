<?php

namespace App\Telegram;

use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class MenuCommand.
 */
class MenuCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var string Command Description
     */
    protected $description = 'Menu command, get bot menu';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $response = $telegram->sendMessage([
            'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
            'text' => 'Меню:',
            'reply_markup' => $this->getMenuReplyMarkup($telegram)
        ]);
        $messageId = $response->getMessageId();
    }

    /**
     * Получение разметки главного меню
     * @param $telegram
     * @return mixed
     */
    public static function getMenuReplyMarkup($telegram){
        $keyboard = [
            ['О нас', 'Новости', 'Инстаграм'],
            ['Адрес', '/Мероприятия'],
        ];
        return $telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);
    }
}
