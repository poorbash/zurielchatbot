<?php

namespace poorbash\ZurielChatBot\App\Middlewares;

use poorbash\ZurielChatBot\App\Models\User;
use SergiX44\Nutgram\Nutgram;

class CollectChatMiddleware
{
    public function __invoke(Nutgram $bot, $next)
    {
        $user = $bot->user();
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $username = $user->username;
        $user = User::firstOrCreate([
            'user_id' => $user->id,
        ], [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'language_id' => $user->language_code,
        ]);
        if (
            $user->first_name !== $firstName
            || $user->last_name !== $lastName
            || $user->username !== $username
        ) {
            $user->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
            ]);
        }
        $bot->setData('user', $user);
        $next($bot);
    }
}