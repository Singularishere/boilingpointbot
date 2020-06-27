<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Users extends Model
{
    protected $fillable = ['name', 'telegramCode', 'apiToken', 'apiRefreshToken'];
    protected $table = 'users';

    /**
     * Получение объекта пользователя по telegram id
     * @param $id
     * @return mixed
     */
    public function getUserByTelegramId($id){
        return $this->where('telegramId',$id)->first();
    }
    /**
     * Запись client id клиента
     * @param $telegramId
     * @param $clientId
     */
    public function setClientId($telegramId, $clientId)
    {

        $user = $this->getUser($telegramId);
        $user->client_id = $clientId;
        $user->save();
    }

    /**
     * Запись secret key клиента
     * @param $telegramId
     * @param $secretKey
     */
    public function setSecretKey($telegramId, $secretKey)
    {
        $user = $this->getUser($telegramId);
        $user->client_secret = $secretKey;
        $user->save();
    }

    /**
     * Запись code клиента
     * @param $telegramId
     * @param $code
     */
    public function setClientApiCode($telegramId,$code){
        $user = $this->getUser($telegramId);
        $user->code = $code;
        $user->save();
    }

    /**
     * Запись access token
     * @param $token
     */
    public function setAccessToken($token){
        $this->apiToken = $token;
        $this->save();
    }

    /**
     * Запись refresh token
     * @param $token
     */
    public function setRefreshToken($token){
        $this->apiRefreshToken = $token;
        $this->save();
    }

    /**
     * Запись user id на платформе leader-id
     * @param $userId
     */
    public function setApiUserId($userId){
        $this->api_user_id = $userId;
        $this->save();
    }

    /**
     * Получить пользователя по telegram id
     * @param $telegramId
     * @return $this
     */
    public function getUser($telegramId)
    {
        return !empty($this->where('telegramId', $telegramId)->first())
            ? $this->select('*')->where('telegramId', $telegramId)->first()
            : $this->setUser($telegramId);
    }

    /**
     * Создание нового пользователя
     * @param $telegramId
     * @return $this
     */
    public function setUser($telegramId)
    {
        $this->name = '';
        $this->email = '';
        $this->telegramId = $telegramId;
        $this->apiToken = '';
        $this->apiRefreshToken = '';
        $this->password = Hash::make($telegramId);
        $this->code = '';
        $this->save();
        return $this;
    }

    /**
     * Получить access token
     * @return mixed
     */
    public function getAccessToken(){
        return $this->apiToken;
    }
}
