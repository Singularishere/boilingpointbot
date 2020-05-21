<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class MainController extends Controller
{
    public function index(Request $request){
//        $telegram = new Api('1227806272:AAFaBHGQCAUMmclvqrd98S1O0BDPrNlwK2w');
//        $result = $telegram->getWebhookUpdates();
////        $fd = fopen("hello.txt", 'w') or die("не удалось создать файл");
////        fwrite($fd, $request->method());
////        fclose($fd);
//
//        $userMessage = $result['message']['text'];
//        $userChatId = $result['message']['chat']['id'];
//        $userChatName = $result['message']['username'];
//        $first_name = $result['message']['from']['first_name'];
//        $last_name = $result['message']['from']['last_name'];
//
//        if($userMessage == '/start'){
//            $reply = 'Hello World';
//            $telegram->sendMessage(['chat_id' => $userChatId, 'text'=>$reply]);
//        }

    }
    public function sendTelegramData($route = '', $params = [],$method = 'POST') {
        $guest = new Client(['base_uri'=>'https://api.telegram.org/bot1227806272:AAFaBHGQCAUMmclvqrd98S1O0BDPrNlwK2w/']);
        $result = $guest->request($method,$route,$params);
        return $result->getBody();
    }
    public function setWebHook(Request $request){
        $result = $this->sendTelegramData('setwebhook',[
            'query' => ['url'=> 'https://boilingpointbot.ru' . '/1227806272:AAFaBHGQCAUMmclvqrd98S1O0BDPrNlwK2w']
        ]);
        return redirect()->route('data')->with('status',$result);
    }
    public function getWebHook(Request $request):string{
        $result  = $this->sendTelegramData('getWebhookInfo');
        return redirect()->route('data')->with('status',$result);
    }
}
