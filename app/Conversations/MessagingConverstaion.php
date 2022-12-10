<?php

namespace poorbash\ZurielChatBot\App\Conversations;

use poorbash\ZurielChatBot\App\Models\Message;
use poorbash\ZurielChatBot\App\Models\User;
use poorbash\ZurielChatBot\App\Telegram\ReplyKeyboard;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class MessagingConverstaion extends Conversation
{
    public function start(Nutgram $bot)
    {
        $bot->sendMessage(appStr('send_your_message'), [
            'reply_markup' => ReplyKeyboard::back()
        ]);
        $this->next('sendToAdmin');
    }

    public function sendToAdmin(Nutgram $bot)
    {
        $fromUser = $bot->getData('user');
        $toUser = User::where([
            'user_id' => appConfig('bot.admin_id'),
        ])->first(['id', 'user_id']);
        $originMessageId = $bot->messageId();
        $response = $bot->forwardMessage($toUser->user_id, $fromUser->user_id, $originMessageId);
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