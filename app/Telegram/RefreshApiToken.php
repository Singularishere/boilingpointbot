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
 * Class Set client api code.
 */
class RefreshApiToken extends Command
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
    protected $name = 'RefreshToken';

    /**
     * @var string Command Description
     */
    protected $description = 'Обновление существующего токена доступа к API';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $requestData = $telegram->getWebhookUpdates();
        $user = $this->user->getUserByTelegramId($requestData['message']['from']['id']);
        if(!empty($user->apiRefreshToken)){
            $apiToken = $this->refreshUserApiToken($user);
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => !empty($apiToken) && ($apiToken instanceof \Exception) ? $apiToken->getMessage() :  "Ваш токен обновлён. Access Token : {$apiToken}",
            ]);
        }else{
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => trans('messages.refresh_token_not_exist'),
            ]);
        }
    }
}
