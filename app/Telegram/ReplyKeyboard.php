<?php

namespace poorbash\ZurielChatBot\App\Telegram;

use poorbash\ZurielChatBot\App\Models\User;

class ReplyKeyboard
{
    public static function main(User $user)
    {
        return self::build([
            [
                ['text' => appStr($user->isAdmin() ? 'btn.stat' : 'btn.messaging')]
            ]
        ]);
    }

    public static function back()
    {
        return self::build([
            [
                ['text' => appStr('btn.back')]
            ]
        ]);
    }

    public static function build(array $keyboard, array $opt = []): string
    {
        $opt += [
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
        ];

        return json_encode(array_merge(['keyboard' => $keyboard], $opt));
    }
}