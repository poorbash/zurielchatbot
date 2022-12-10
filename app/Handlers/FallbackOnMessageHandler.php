<?php

namespace poorbash\ZurielChatBot\App\Handlers;

use poorbash\ZurielChatBot\App\Exceptions\BotException;
use poorbash\ZurielChatBot\App\Models\Message;
use poorbash\ZurielChatBot\App\Telegram\ReplyKeyboard;
use SergiX44\Nutgram\Nutgram;

class FallbackOnMessageHandler
{
    public function __invoke(Nutgram $bot)
    {
        $repliedMessageId = $bot->message()->reply_to_message->message_id;
        $message = Message::where([
            'destination_message_id' => $repliedMessageId,
        ])->first(['id', 'from_user_id']);
        if (!$message) {
            $user = $bot->getData('user');
            throw new BotException(appStr('unknown_command'), [
                'reply_markup' => ReplyKeyboard::main($user),
            ]);
        } else {
            $fromUser = $bot->getData('user');
            $toUser = $message->fromUser()->first(['id', 'user_id']);
            $originMessageId = $bot->messageId();
            $response = $bot->copyMessage($toUser->user_id, $fromUser->user_id, $originMessageId);
            $destinationMessageId = $response->message_id;
            Message::create([
                'from_user_id' => $fromUser->id,
                'to_user_id' => $toUser->id,
                'origin_message_id' => $originMessageId,
                'destination_message_id' => $destinationMessageId,
            ]);
            $bot->sendMessage(appStr('message_sent'), [
                'reply_to_message_id' => $originMessageId
            ]);
        }
    }
}