<?php

namespace poorbash\ZurielChatBot\App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'username',
        'language_code',
    ];

    protected $attributes = [
        'first_name' => null,
        'last_name' => null,
        'username' => null,
        'language_code' => 'fa',
    ];

    public function isAdmin(): bool
    {
        return appConfig('bot.admin_id') === $this->user_id;
    }
}