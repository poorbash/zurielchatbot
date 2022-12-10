<?php

namespace poorbash\ZurielChatBot\App\Commands;

use poorbash\ZurielChatBot\App\Telegram\ReplyKeyboard;
use SergiX44\Nutgram\Nutgram;

class BackCommand
{
    public function __invoke(Nutgram $bot)
    {
        $user = $bot->getData('user');
        $bot->sendMessage(appStr('main_menu'), [
            'reply_markup' => ReplyKeyboard::main($user)
        ]);
    }
}