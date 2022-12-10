<?php

namespace poorbash\ZurielChatBot\App\Middlewares;

use poorbash\DigiSave\Class\Telegram;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

class CheckUpdateTypeMiddleware
{
    public function __invoke(Nutgram $bot, $next)
    {
        $updateType = $bot->update()->getType();
        $allowedUpdateTypes = [
            UpdateTypes::MESSAGE,
        ];
        if (!in_array($updateType, $allowedUpdateTypes)) {
            die();
        }
        $next($bot);
    }
}