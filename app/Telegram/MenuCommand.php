<?php

namespace App\Telegram;

use App\Http\Models\Users;
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
        $requestData = $telegram->getWebhookUpdates();
        $user = new Users();
        $registeredUser = $user->getUserByTelegramId($requestData['message']['from']['id']);
        $telegram->sendMessage([
            'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
            'text' => empty($registeredUser)?'Здравствуйте, вас приветствует телеграм бот площадки "Точка кипения". Для того, чтобы использовать функции api и команды этого бота, необходимо зарегистрироваться. Для этого в меню выберите пункт Авторизация.'."\nМеню:":"Меню:",
            'reply_markup' => $this->getMenuReplyMarkup($telegram)
        ]);
    }

    /**
     * Получение разметки главного меню
     * @param $telegram
     * @return mixed
     */
    public static function getMenuReplyMarkup($telegram)
    {
        $keyboard = [
            ['О нас', 'Авторизация', 'Аккаунт'],
            ['Мероприятия', 'Инстаграм'],
        ];
        return $telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);
    }
}
