<?php

namespace poorbash\ZurielChatBot\App\Middlewares;

use SergiX44\Nutgram\Nutgram;

class CheckChatTypeMiddleware
{
    public function __invoke(Nutgram $bot, $next)
    {
        $chatType = $bot->chat()->type;
        if ($chatType !== 'private') {
            $bot->leaveChat($bot->chatId());
            die();
        }
        if ($bot->user()->is_bot) {
            die();
        }
        $next($bot);
    }
}