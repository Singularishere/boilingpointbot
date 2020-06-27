<?php

namespace App\Http\Traits;

use App\Http\Models\Users;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Hash;

trait Api
{

    /**
     * Получение мероприятий по дате
     * @param $date
     * @param $user
     * @return array
     * @throws GuzzleException
     */
    public function getEventsByDate($date,$user): array
    {
        $client = new Client(['base_uri' => 'https://leader-id.ru/']);
        $url = "api/events?access_token={$user->apiToken}&StartDate={$date[0]}&EndDate={$date[1]}";
        $response = $client->request("GET", $url);
        $parsedData = json_decode($response->getBody()->getContents());
        $returnData = array();
        foreach ($parsedData->Data as $k => $item) {
            if (property_exists($item, 'Name')) {
                $returnData[$k]['Name'] = $item->Name;
            }
            if (property_exists($item, 'Info')) {
                $returnData[$k]['Info'] = $item->Info;
            }
            if (property_exists($item, 'Url')) {
                $returnData[$k]['Url'] = $item->Url;
            }
        }
        return $returnData;
    }

    /**
     * Получение списка мероприятий пользователя
     * @return array
     * @throws GuzzleException
     */
    public function getUserEventsList(Users $user): array
    {
        $client = new Client(['base_uri' => 'https://leader-id.ru/']);
        try {
            $url = "api/users/{$user->api_user_id}/events?access_token={$user->apiToken}";
            $response = $client->request("GET", $url);
            $parsedData = json_decode($response->getBody()->getContents());
            return count($parsedData->Data) > 0 ? $parsedData->Data : ['error' => 'Вы не подписаны на мероприятия'];
        } catch (\Exception $exception) {
            if ($exception->getCode() == '404') {
                return [
                    'error' => $exception->getResponse()->getreasonPhrase()
                ];
            }
        }
    }

    /**
     * Добавление заявки на мероприятие по id
     * @param $eventId id-мероприятия
     * @param $user
     * @return string
     * @throws GuzzleException
     */
    public function registerEvent($eventId, $user): string
    {
        $client = new Client(['base_uri' => 'https://leader-id.ru/']);
        try{
            $url = "api/events/$eventId/register/{$user->api_user_id}?access_token={$user->apiToken}";
            $response = $client->request("POST", $url);
            $parsedData = json_decode($response->getBody()->getContents());
            if (!empty($parsedData) && $parsedData->Result == true) {
                return $parsedData->Status == 'Unregistered' ? 'Заявка на мероприятие отменена' : 'Заявка на мероприятие успешно подана';
            }
        }catch (\Exception $exception){
            return  $exception;
        }
//        $url = "api/events/$eventId/register/{$user->api_user_id}?access_token={$user->apiToken}";
//        $response = $client->request("POST", $url);
//        $parsedData = json_decode($response->getBody()->getContents());
//        if (!empty($parsedData) && $parsedData->Result == true) {
//            return $parsedData->Status == 'Unregistered' ? 'Заявка на мероприятие отменена' : 'Заявка на мероприятие успешно подана';
//        }
//        return $parsedData->error_description;
    }

    /**
     * Получение площадки по id
     * @param Users $user
     * @param $areaId
     * @throws GuzzleException
     */
    public function getArea(Users $user, $areaId)
    {
        $client = new Client(['base_uri' => 'https://leader-id.ru/']);
        try {
            $response = $client->request("GET", "api/addresses/$areaId?access_token={$user->apiToken}");
            $parsedData = json_decode($response->getBody()->getContents());
            $countryName = $client->request("GET", "api/countries/{$parsedData->Data->CountryId}?access_token={$user->apiToken}");
            $regionName = $client->request("GET", "api/regions/{$parsedData->Data->RegionId}?access_token={$user->apiToken}");
            $cityName = $client->request("GET", "api/cities/{$parsedData->Data->CityId}?access_token={$user->apiToken}");
            $parsedData->Data->CountryName = json_decode($countryName->getBody()->getContents())->Data->Name;
            $parsedData->Data->RegionName = json_decode($regionName->getBody()->getContents())->Data->Name;
            $parsedData->Data->CityName = json_decode($cityName->getBody()->getContents())->Data->Name;
            return $parsedData->Data;
        } catch (\Exception $exception) {
            if ($exception->getCode() == 403) {
                $user->getAccessToken();
                if (empty($user->getAccessToken())) {
                    return ['error' => trans('messages.access_token_error')];
                } else {
                    return $exception->getMessage();
//                    $this->refreshUserApiToken($user);
//                    $this->getArea($user,$areaId);
                }
            }
            return [
                'error' => $exception->getResponse()->getreasonPhrase() . '' . $exception->getPrevious()
            ];
        }

    }

    /**
     * Метод получения токена для доступа к API
     * @param Users $user
     * @return mixed|string
     * @throws GuzzleException
     */
    private function getUserApiToken(Users $user)
    {
        $client = new Client(['base_uri' => 'https://leader-id.ru/']);
        try {
            $url = "api/oauth/access_token?grant_type=authorization_code&code={$user->code}&client_id={$user->client_id}&client_secret={$user->client_secret}&redirect_uri=boilingpointbot.ru";
            $response = $client->request("POST", $url);
            $parsedData = json_decode($response->getBody()->getContents());
            $user->setAccessToken($parsedData->access_token);
            $user->setRefreshToken($parsedData->refresh_token);
            $user->setApiUserId($parsedData->user_id);
            return $user->apiToken;
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

    }

    /**
     * Метод обновления токена для доступа к API
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function refreshUserApiToken(Users $user)
    {
        $client = new Client(['base_uri' => 'https://leader-id.ru/']);
        try {
            $response = $client->request("POST", "oauth/access_token?client_id={$user->client_id}&client_secret={$user->client_secret}&refresh_token={$user->apiRefreshToken}&grant_type=refresh_token");
            $parsedData = json_decode($response->getBody()->getContents());
            $user->setAccessToken($parsedData->access_token);
            $user->setRefreshToken($parsedData->refresh_token);
            $user->setApiUserId($parsedData->user_id);
            return $user->apiToken;
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
