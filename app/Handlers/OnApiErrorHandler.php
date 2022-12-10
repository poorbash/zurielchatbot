<?php

namespace poorbash\ZurielChatBot\App\Handlers;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;

class OnApiErrorHandler
{
    public function __invoke(Nutgram $bot, TelegramException $ex)
    {
        $bot->sendMessage(appStr('unknown_error'));
        error_log(strval($ex));
    }

    public function doNothing(Nutgram $bot, TelegramException $ex)
    {
        return;
    }
}