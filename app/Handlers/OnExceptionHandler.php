<?php

namespace poorbash\ZurielChatBot\App\Handlers;

use poorbash\ZurielChatBot\App\Exceptions\BotException;
use SergiX44\Nutgram\Nutgram;

class OnExceptionHandler
{
    public function __invoke(Nutgram $bot, \Throwable $th)
    {
        $bot->sendMessage(appStr('unknown_error'));
        error_log(strval($th));
    }

    public function bot(Nutgram $bot, BotException $ex)
    {
        $bot->sendMessage($ex->getMessage(), $ex->getParams());
    }
}