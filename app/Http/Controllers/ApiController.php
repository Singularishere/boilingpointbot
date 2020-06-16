<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Models\Users;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Users
     */
    protected $users;

    public function __construct(Users $users)
    {
        $this->client = new Client(['base_uri'=>'https://leader-id.ru/']);
        $this->users = $users;
    }
    public function index(){

    }
    public function getUserSubscribeEvents(){
        $this->users->setTelegramCode(1234263);
        $response = $this->client->request('GET','api/users/1234263/events?access_token=2ef580c53b8f93dc');
        $data = json_decode($response->getBody()->getContents());
        dump();
    }

    /**
     * Получение мероприятий по дате
     * @param $date
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getEventsByDate($date){
        $response = $this->client->request("GET","api/events?access_token=0195ea449f0865fb&StartDate=$date[0]&EndDate=$date[1]");
        $parsedData = json_decode($response->getBody()->getContents());
        return;
    }
}
