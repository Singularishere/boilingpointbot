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
        //Массив с ключами, являющимися именами классов.
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
            'GetEventById' => 'Выбор мероприятия по id'];
        $data = $telegram->getWebhookUpdates();
        $message = $data['message']['text'];
        //Создание динамического класса команды меню
        $className = array_search(trim($message), $menuCommandsArr);
        if ($className != false) {
            $className = 'App\\ClassLibrary\\' . $className;
            $menuClass = new $className();
            $telegram->sendMessage($menuClass->getResponseData($telegram, $data['message']['from']['id']));
        } else {
            Telegram::commandsHandler(true);
        }

    }
}
