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

    }

    public function sendTelegramData($route = '', $params = [],$method = 'POST') {
        $guest = new Client(['base_uri'=>'https://api.telegram.org/bot1227806272:AAFaBHGQCAUMmclvqrd98S1O0BDPrNlwK2w/']);
        $result = $guest->request($method,$route,$params);
        return $result->getBody();
    }

    /**
     * Установка веб хука
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setWebHook(Request $request){
        $result = $this->sendTelegramData('setwebhook',[
            'query' => ['url'=> 'https://boilingpointbot.ru' . '/1227806272:AAFaBHGQCAUMmclvqrd98S1O0BDPrNlwK2w']
        ]);
        return redirect()->route('data')->with('status',$result);
    }

    /**
     * Получение веб хука
     * @param Request $request
     * @return string
     */
    public function getWebHook(Request $request):string{
        $result  = $this->sendTelegramData('getWebhookInfo');
        return redirect()->route('data')->with('status',$result);
    }
}
