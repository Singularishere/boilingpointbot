<?php

namespace App\Telegram;

use App\Http\Models\Users;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Actions;
use Telegram\Bot\Laravel\Facades\Telegram;
use function GuzzleHttp\Psr7\str;
use App\Http\Traits;

/**
 * Class Get Event By Id.
 */
class GetEventById extends Command
{
    use Traits\Api;

    /**
     * @var Users
     */
    private $user;

    public function __construct()
    {
        $this->user = new Users();
    }

    /**
     * @var string Command Name
     */
    protected $name = 'GetEventById';

    /**
     * @var string Command Description
     */
    protected $description = 'Получение информации о мероприятии по id мероприятия';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $requestData = $telegram->getWebhookUpdates();
        $requestText = explode(':', str_replace(' ', '', $requestData['message']['text']));
        if (isset($requestText[1])) {
            $event = $this->getEventById($this->user->getUserByTelegramId($requestData['message']['from']['id']), (int)$requestText[1]);
            $telegram->sendMessage([
                'chat_id' => $requestData['message']['from']['id'],
                'text' => !empty($event) && $event instanceof \Exception ? $event->getMessage() : $this->getEventTransformData($event),
            ]);
        } else {
            $telegram->sendMessage([
                'chat_id' => $requestData['message']['from']['id'],
                'text' => 'Чтобы отправить заявку на мероприятие введите команду /GetEventById : id мероприятия'
            ]);
        }

    }

    /**
     * Преобразование информации о мероприятии в читабельный формат
     * @param $event
     * @return string
     */
    private function getEventTransformData($event): string
    {
        $text = '';
        $text .= "\nId мероприятия: {$event->Id}";
        $text .= "\nНазвание: {$event->Name}";
        $text .= "\nКраткая информация: {$event->Info}";
        $text .= "\nДата и время начала: {$event->StartDate} {$event->StartTime}";
        $text .= "\nДата и время окончания: {$event->EndDate} {$event->EndTime}";
        $text .= "\nАдрес: {$event->Address->Full}";
        $text .= "\nКонтакты: \n-Email : {$event->Contacts->Email}\n-Телефон : {$event->Contacts->Phone}\n-Пользователь: {$event->Contacts->UserFullName}";
        $text .= "\nСсылка: {$event->Url}";
        return $text;
    }
}
