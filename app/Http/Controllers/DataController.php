<?php

namespace App\Http\Controllers;

use App\Http\Models\Users;
use App\Http\Traits\Api;
use App\Telegram\EventsCommand;
use GuzzleHttp\Middleware;
use GuzzleHttp\TransferStats;
use http\Client;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Psr\Http\Message\RequestInterface;
use Telegram\Bot\Laravel\Facades\Telegram;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use App\Telegram\MenuCommand;

class DataController extends Controller
{
    /**
     * Использование трейта API
     */
    use Api;


    public function index()
    {
        return view('data');
    }

    public function handleTelegramData()
    {
        $telegram = new \Telegram\Bot\Api(Telegram::getAccessToken(), true);
        //Массив с ключами, являющимися именами классов и значений.
        $menuCommandsArr = [
            'AboutUs' => 'О нас',
            'Authorize' => 'Авторизация',
            'Instagram' => 'Инстаграм',
            'Account' => 'Аккаунт',
            'Events' => 'Мероприятия',
            'BackToMainMenu' => 'Вернуться в главное меню',
            'EventsByDate' => 'Выбор перечня мероприятий в график',
            'EventsById' => 'Добавление заявки по id мероприятия',
            'SubscribedEvents' => 'Перечень мероприятий по дате на который вы подписаны',
            'AreaById' => 'Выбор конкретной площадки по ID'];
        $data = $telegram->getWebhookUpdates();
        $message = $data['message']['text'];
        //Меню
        $className = array_search(trim($message), $menuCommandsArr);
        if ($className != false) {
            $className = 'App\\ClassLibrary\\' . $className;
            $menuClass = new $className();
            $telegram->sendMessage($menuClass->getResponseData($telegram, $data['message']['from']['id']));
        }else{
            Telegram::commandsHandler(true);
        }

        $parsedMessage = explode('?', $message);
//
//        /**
//         * Проверка на существование 2 аргумента после парсинга запроса
//         */
//        if (isset($parsedMessage[2])) {
//            if ($parsedMessage[1] == 'Мероприятие в график') {
//                $parsedMessage[2] = str_replace(' ', '', $parsedMessage[2]);
//                $parsedDate = explode('-', $parsedMessage[2]);
//                $text = $this->getEventsByDate($parsedDate);
//                foreach ($text as $item) {
//                    $telegram->sendMessage([
//                        'chat_id' => $data['message']['from']['id'],
//                        'text' => "Название:  {$item['Name']}\r\nИнформация:  {$item['Info']}\r\nUrl:   {$item['Url']}",
//                    ]);
//                }
//            }
//            if ($parsedMessage[1] == 'Добавление мероприятия') {
//                //TODO: найти пользователя в базе по telegram_id
//                $data['message']['from']['id'];
//                $param1 = 52063;
//                $telegram->sendMessage([
//                    'chat_id' => $data['message']['from']['id'],
//                    'text' => $this->registerEvent((int)$parsedMessage[2], 1234263),
//                ]);
//            }
//            if ($parsedMessage[1] == 'Площадка') {
//                $areas = $this->getArea((int)$parsedMessage[2]);
//                $telegram->sendMessage([
//                    'chat_id' => $data['message']['from']['id'],
//                    'text' => array_key_exists('error', $areas) ? $areas['error'] : "$areas->CountryName , $areas->CityName"
//                ]);
//            }
//        }
//        if (in_array($data['message']['text'], $menuCommandsArr)) {
//            if ($data['message']['text'] == 'Выбор перечня мероприятий в график') {
//                $telegram->sendMessage([
//                    'chat_id' => $data['message']['from']['id'],
//                    'text' => 'Пришлите ответ в формате:
//?Мероприятие в график?дд.мм.гггг - дд.мм.гггг',
//                ]);
//            }
//            if ($data['message']['text'] == 'Добавление заявки по id мероприятия') {
//                $telegram->sendMessage([
//                    'chat_id' => $data['message']['from']['id'],
//                    'text' => 'Пришлите ответ в формате:
//?Добавление мероприятия?id',
//                ]);
//            }
//            if ($data['message']['text'] == 'Перечень мероприятий по дате на который вы подписаны') {
//                $apiData = $this->getUserEventsList(1234263);
//                if (array_key_exists('error', $apiData)) {
//                    $telegram->sendMessage([
//                        'chat_id' => $data['message']['from']['id'],
//                        'text' => $apiData['error'],
//                    ]);
//                } else {
//                    foreach ($apiData as $item) {
//                        $telegram->sendMessage([
//                            'chat_id' => $data['message']['from']['id'],
//                            'text' => "Id мероприятия: $item->Id\r\nНазвание мероприятия: $item->Name\r\nИнформация о мероприятии: $item->Info",
//                        ]);
//                    }
//                }
//
//            }
//            if ($data['message']['text'] == 'Выбор конкретной площадки по ID') {
//                $telegram->sendMessage([
//                    'chat_id' => $data['message']['from']['id'],
//                    'text' => "Для того чтобы выбрать конкретную площадку по id выполните следующую команду: /GetAreaById : id площадки",
//                ]);
//            }

    }

    /**
     * ? Имя класса ?
     */
    public function test()
    {
        $user = new Users();
        $areas = $this->getArea($user->getUserByTelegramId(223), 2222);
        $apiToken = $this->getUserApiToken($user->getUserByTelegramId(223));
        dump($apiToken);
        die();
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://leader-id.ru/']);
        $response = $client->request("GET", "api/events?access_token=0195ea449f0865fb&StartDate=26.06.2020&EndDate=02.08.2020");
        $parsedData = json_decode($response->getBody()->getContents());
//        $returnData = array();
//        foreach ($parsedData->Data as $k=>$item){
//            if(property_exists($item,'Name')){
//                $returnData[$k]['Name'] = $item->Name;
//            }
//            if(property_exists($item,'Info')){
//                $returnData[$k]['Info'] = $item->Info;
//            }
//            if(property_exists($item,'Url')){
//                $returnData[$k]['Url'] = $item->Url;
//            }
//        }
//        $returnData = array_map(function ($k,$v){
//            return $k == 'Name'||'Info'||'Url';
//        },array_keys($parsedData->Data),$parsedData->Data);
        trim($message = '?Мероприятие в график?22.06.2290 - 30.04.3030');
        $parsedMessage = explode('?', $message);
        if (isset($parsedMessage[2])) {
            $parsedMessage[2] = str_replace(' ', '', $parsedMessage[2]);
            $parsedDate = explode('-', $parsedMessage[2]);
            dump("try:{$parsedDate[0]}");
            die();
        }

    }
}
