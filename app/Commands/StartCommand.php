<?php

namespace poorbash\ZurielChatBot\App\Commands;

use poorbash\ZurielChatBot\App\Telegram\ReplyKeyboard;
use SergiX44\Nutgram\Nutgram;

class StartCommand
{
    public function __invoke(Nutgram $bot)
    {
        $user = $bot->getData('user');
        $bot->sendMessage(appStr($user->isAdmin() ? 'start_msg_for_admin' : 'start_msg_for_users'), [
            'reply_markup' => ReplyKeyboard::main($user)
        ]);
    }
}