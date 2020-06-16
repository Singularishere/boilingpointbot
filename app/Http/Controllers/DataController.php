<?php

namespace App\Http\Controllers;

use App\Http\Traits\Api;
use GuzzleHttp\Middleware;
use GuzzleHttp\TransferStats;
use http\Client;

//use Illuminate\Http\Request;
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
        $menuCommandsArr = [
            'О нас', 'Авторизация', 'Инстаграм',
            'Адрес', 'Вернуться в главное меню', 'Выбор перечня мероприятий в график',
            'Добавление заявки по id мероприятия','Перечень мероприятий по дате на который вы подписаны','Выбор конкретной площадки по ID'];
        $data = $telegram->getWebhookUpdates();
        $message = $data['message']['text'];
        $parsedMessage = explode('?', $message);
        /**
         * Проверка на существование 2 аргумента после парсинга запроса
         */
        if (isset($parsedMessage[2])) {
            if ($parsedMessage[1] == 'Мероприятие в график') {
                $parsedMessage[2] = str_replace(' ', '', $parsedMessage[2]);
                $parsedDate = explode('-', $parsedMessage[2]);
                $text = $this->getEventsByDate($parsedDate);
                foreach ($text as $item) {
                    $telegram->sendMessage([
                        'chat_id' => $data['message']['from']['id'],
                        'text' => "Название:  {$item['Name']}\r\nИнформация:  {$item['Info']}\r\nUrl:   {$item['Url']}",
                    ]);
                }
            }
            if($parsedMessage[1] == 'Добавление мероприятия'){
                //TODO: найти пользователя в базе по telegram_id
                $data['message']['from']['id'];
                $param1 = 52063;
                $telegram->sendMessage([
                    'chat_id' => $data['message']['from']['id'],
                    'text' => $this->registerEvent((int)$parsedMessage[2],1234263),
                ]);
            }
            if($parsedMessage[1] == 'Площадка'){
                $areas = $this->getArea((int)$parsedMessage[2]);
                $telegram->sendMessage([
                    'chat_id' => $data['message']['from']['id'],
                    'text' => array_key_exists('error',$areas)?$areas['error']: "$areas->CountryName , $areas->CityName"
                ]);
            }
        }
        if (in_array($data['message']['text'], $menuCommandsArr)) {
            switch ($data['message']['text']) {
                case 'О нас':
                    $text = '«Точка кипения» — формат пространства коллективной работы, в котором каждый человек или команда получают возможность обмена опытом, свободный доступ к знаниям, профессиональным сообществам и авторитетным экспертам, новым идеям и технологиям. «Точка кипения» служит инструментом поиска, вовлечения, развития и продвижения лидеров, команд, проектов и инициатив, оказывающих значимое влияние на социально-экономическое развитие России в области образования, государственного и муниципального управления, цифровой экономики и технологического развития.

КЛЮЧЕВЫЕ ПРИНЦИПЫ «ТОЧКИ КИПЕНИЯ»

- Независимость пространства. В «Точку кипения» может прийти любой человек, провести мероприятие, поработать

- Обсуждение «без галстуков». Доверительная атмосфера

- Социальный лифт. Ценность отдельных личностей и команд

- Пространство «Точки кипения» — место построения персональных образовательных траекторий

- Пространство — навигатор смыслов. «Точка кипения» становится навигатором по повестке агентства, Университета 20.35 и повестке партнеров

- Содействие развитию Национальной технологической инициативы

- Отсутствие политики и религии';
                    $telegram->sendMessage([
                        'chat_id' => $data['message']['from']['id'],
                        'text' => $text,
                    ]);
                    break;
                case 'Инстаграм':
                    $text = 'https://www.instagram.com/tochkak/';
                    $telegram->sendMessage([
                        'chat_id' => $data['message']['from']['id'],
                        'text' => $text,
                    ]);
                    break;
                case 'Вернуться в главное меню':
                    $telegram->sendMessage([
                        'chat_id' => $data['message']['from']['id'],
                        'text' => '/start',
                        'reply_markup' => MenuCommand::getMenuReplyMarkup($telegram)
                    ]);
                    return;

            }
            if ($data['message']['text'] == 'Выбор перечня мероприятий в график') {
                $telegram->sendMessage([
                    'chat_id' => $data['message']['from']['id'],
                    'text' => 'Пришлите ответ в формате:
?Мероприятие в график?дд.мм.гггг - дд.мм.гггг',
                ]);
            }
            if($data['message']['text'] == 'Добавление заявки по id мероприятия'){
                $telegram->sendMessage([
                    'chat_id' => $data['message']['from']['id'],
                    'text' => 'Пришлите ответ в формате:
?Добавление мероприятия?id',
                ]);
            }
            if($data['message']['text'] == 'Перечень мероприятий по дате на который вы подписаны'){
                $apiData = $this->getUserEventsList(1234263);
                if(array_key_exists('error',$apiData)){
                    $telegram->sendMessage([
                        'chat_id' => $data['message']['from']['id'],
                        'text' => $apiData['error'],
                    ]);
                }else{
                    foreach ($apiData as $item){
                        $telegram->sendMessage([
                            'chat_id' => $data['message']['from']['id'],
                            'text' => "Id мероприятия: $item->Id\r\nНазвание мероприятия: $item->Name\r\nИнформация о мероприятии: $item->Info",
                        ]);
                    }
                }

            }
            if($data['message']['text'] == 'Выбор конкретной площадки по ID'){
                $telegram->sendMessage([
                    'chat_id' => $data['message']['from']['id'],
                    'text' => "Пришлите ответ в формате:\r\n?Площадка?id площадки",
                ]);
            }
            if($data['message']['text'] == 'Авторизация'){
                $telegram->sendMessage([
                    'chat_id' => $data['message']['from']['id'],
                    'text' => 'Авторизация',
                ]);
            }
            if ($data['message']['text'] == 'Адрес') {
                $telegram->sendMessage([
                    'chat_id' => $data['message']['from']['id'],
                    'text' => 'Наш адрес на картах: ' . $data['message']['from']['id'],
                ]);
                $telegram->sendLocation([
                    'chat_id' => $data['message']['from']['id'],
                    'latitude' => 47.219787,
                    'longitude' => 39.712465,
                ]);

            }
        } else {
            Telegram::commandsHandler(true);
        };
    }

    public function authorizeLeaderApi()
    {
        $code = '97b7976af08dea1bd0e5';
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://leader-id.ru/']);
        $response = $client->request('POST', 'https://leader-id.ru/oauth/access_token?
client_id=0e98e84d84bcba7eb73f64d667b99638&
client_secret=b8bb22176cd066d6e2599993862f1036&
code=78194a547b3b9fd6925d&
redirect_uri=boilingpointbot.ru
grant_type=authorization_code');
        return response()->json($response);
    }

    public function getAccessCode()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://leader-id.ru/']);
        $onRedirect = function (
            RequestInterface $request,
            ResponseInterface $response,
            UriInterface $uri
        ) {
            echo 'Redirecting! ' . $request->getUri() . ' to ' . $uri . "\n";
        };
        $response = $client->request('POST', 'oauth/authorize?client_id=0e98e84d84bcba7eb73f64d667b99638&redirect_uri=boilingpointbot.ru&response_type=code', [
            'on_stats' => function (TransferStats $stats) use (&$redir) {
                $redir = $stats->getRequest();
            }
        ]);
        dump($redir); //OK
        exit();
    }

    /**
     * @return bool
     */
    public function getCitiesApi()
    {
        $accessToken = Config::get('api.access_token');
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://leader-id.ru/api/']);
        $response = $client->request('GET', 'cities?access_token=2ef580c53b8f93dc');
        $parseData = json_decode($response->getBody())->Data;
        if (is_array($parseData)) {
            $sortData = array_filter($parseData, function ($v, $k) {
                return $v->CountryId == 160;
            }, ARRAY_FILTER_USE_BOTH);
//            array_map(function ($item,$key) use ($parseData) {
//                if((int)$item->array_filter != 160){
//                    unset($parseData[$key]);
//                }
//            },$parseData, array_keys($parseData));
        } else {
            return false;
        }
    }

    /**
     * ? Имя класса ?
     */
    public function test()
    {
        dump($this->getArea(881));
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
