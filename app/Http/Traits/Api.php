<?php
namespace App\Http\Traits;

use App\Http\Models\Users;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait Api {


    /**
     * Получение мероприятий по дате
     * @param $date
     * @return array
     * @throws GuzzleException
     */
    public function getEventsByDate($date):array {
        $client = new Client(['base_uri'=>'https://leader-id.ru/']);
        $response = $client->request("GET","api/events?access_token=4d864461d5d20a59&StartDate={$date[0]}&EndDate={$date[1]}");
        $parsedData = json_decode($response->getBody()->getContents());
        $returnData = array();
        foreach ($parsedData->Data as $k=>$item){
            if(property_exists($item,'Name')){
                $returnData[$k]['Name'] = $item->Name;
            }
            if(property_exists($item,'Info')){
                $returnData[$k]['Info'] = $item->Info;
            }
            if(property_exists($item,'Url')){
                $returnData[$k]['Url'] = $item->Url;
            }
        }
        return $returnData;
    }

    /**
     * Получение списка мероприятий пользователя
     * @param $userId
     * @return array
     * @throws GuzzleException
     */
    public function getUserEventsList($userId):array {
        $client = new Client(['base_uri'=>'https://leader-id.ru/']);
        $responseData = array();
        try {
            $response = $client->request("GET","api/users/$userId/events?access_token=4d864461d5d20a59");
            $parsedData = json_decode($response->getBody()->getContents());
            return count($parsedData->Data) > 0 ?$parsedData->Data : ['error'=>'Вы не подписаны на мероприятия'];
        }
        catch (\Exception $exception){
            if($exception->getCode() == '404'){
                return [
                    'error' => $exception->getResponse()->getreasonPhrase()
                ];
            }
        }
    }

    /**
     * Добавление заявки на мероприятие по id
     * @param $eventId
     * @param $userId
     * @return string
     * @throws GuzzleException
     */
    public function registerEvent($eventId,$userId):string {
        $client = new Client(['base_uri'=>'https://leader-id.ru/']);
        $response = $client->request("POST","api/events/$eventId/register/$userId?access_token=4d864461d5d20a59");
        $parsedData = json_decode($response->getBody()->getContents());
        if(!empty($parsedData) && $parsedData->Result == true){
            return $parsedData->Status == 'Unregistered'? 'Заявка на мероприятие отменена' : 'Заявка на мероприятие успешно подана';
        }
        return $parsedData->error_description;
    }

    /**
     * @param $areaId
     * @return object|array
     * @throws GuzzleException
     */
    public function getArea($areaId){
        $client = new Client(['base_uri'=>'https://leader-id.ru/']);
//        $areaId = 89078;
        try {
            $response = $client->request("GET","api/addresses/$areaId?access_token=4d864461d5d20a59");
            $parsedData = json_decode($response->getBody()->getContents());

            $countryName = $client->request("GET","api/countries/{$parsedData->Data->CountryId}?access_token=4d864461d5d20a59");
            $regionName = $client->request("GET","api/regions/{$parsedData->Data->RegionId}?access_token=4d864461d5d20a59");
            $cityName = $client->request("GET", "api/cities/{$parsedData->Data->CityId}?access_token=4d864461d5d20a59");
            $parsedData->Data->CountryName = json_decode($countryName->getBody()->getContents())->Data->Name;
            $parsedData->Data->RegionName = json_decode($regionName->getBody()->getContents())->Data->Name;
            $parsedData->Data->CityName = json_decode($cityName->getBody()->getContents())->Data->Name;
            return  $parsedData->Data;
        }catch (\Exception $exception){
            if($exception->getCode() == '404'){
                return [
                    'error' => $exception->getResponse()->getreasonPhrase()
                ];
            }
        }

    }
}
