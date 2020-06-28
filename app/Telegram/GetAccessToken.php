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
 * Class Get access token.
 */
class GetAccessToken extends Command
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
    protected $name = 'GetAccessToken';

    /**
     * @var string Command Description
     */
    protected $description = 'Получение access token, при условии что у вашего аккаунта привязаны code, client_id и client_secret';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $telegram = new Api(Telegram::getAccessToken());
        $requestData = $telegram->getWebhookUpdates();
        $user = $this->user->getUserByTelegramId($requestData['message']['from']['id']);
        if (empty($user->apiToken)) {
            $apiToken = $this->getUserApiToken($user);
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => !empty($apiToken) && isset($apiToken['error']) ? $apiToken['error'] : "Ваш Api Token: {$apiToken}",
            ]);
        } else {
            $telegram->sendMessage([
                'chat_id' => $telegram->getWebhookUpdates()['message']['from']['id'],
                'text' => 'К вашему аккаунту уже привязан access token. Чтобы обновить его, выполните команду /RefreshToken.',
            ]);
        }
    }
}
