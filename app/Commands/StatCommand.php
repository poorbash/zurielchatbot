<?php

namespace poorbash\ZurielChatBot\App\Commands;

use poorbash\ZurielChatBot\App\Models\User;
use SergiX44\Nutgram\Nutgram;

class StatCommand
{
    public function __invoke(Nutgram $bot)
    {
        $count = User::count();
        $bot->sendMessage(strtr(appStr('bot_stat'), [
            ':number_of_users' => $count ?? 50,
        ]));
    }
}