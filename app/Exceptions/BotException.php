<?php

namespace poorbash\ZurielChatBot\App\Exceptions;

class BotException extends \Exception 
{
    private array $params = [];

    public function __construct(string $string, array $params = [])
    {
        $this->params = $params;
        
        parent::__construct($string);
    }

    public function getParams(): array
    {
        return $this->params;
    }
}