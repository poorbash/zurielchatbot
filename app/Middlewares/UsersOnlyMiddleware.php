<?php

namespace poorbash\ZurielChatBot\App\Middlewares;

use poorbash\ZurielChatBot\App\Exceptions\BotException;
use poorbash\ZurielChatBot\App\Telegram\ReplyKeyboard;
use SergiX44\Nutgram\Nutgram;

class UsersOnlyMiddleware
{
    public function __invoke(Nutgram $bot, $next)
    {
        $user = $bot->getData('user');
        if ($user->isAdmin()) {
            throw new BotException(appStr('users_only'), [
                'reply_markup' => ReplyKeyboard::main($user),
            ]);
        }
        $next($bot);
    }
}