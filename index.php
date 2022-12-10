<?php declare(strict_types=1);

/* -------------------------------------------------------------------------- */

use poorbash\ZurielChatBot\App\Middlewares\CheckChatTypeMiddleware;
use poorbash\ZurielChatBot\App\Middlewares\CheckUpdateTypeMiddleware;
use poorbash\ZurielChatBot\App\Middlewares\CollectChatMiddleware;
use poorbash\ZurielChatBot\App\Handlers\OnApiErrorHandler;
use poorbash\ZurielChatBot\App\Handlers\OnExceptionHandler;
use poorbash\ZurielChatBot\App\Conversations\MessagingConverstaion;
use poorbash\ZurielChatBot\App\Commands\BackCommand;
use poorbash\ZurielChatBot\App\Commands\StartCommand;
use poorbash\ZurielChatBot\App\Commands\StatCommand;
use poorbash\ZurielChatBot\App\Exceptions\BotException;
use poorbash\ZurielChatBot\App\Handlers\FallbackOnMessageHandler;
use poorbash\ZurielChatBot\App\Middlewares\AdminsOnlyMiddleware;
use poorbash\ZurielChatBot\App\Middlewares\UsersOnlyMiddleware;
use Illuminate\Database\Capsule\Manager;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

/* -------------------------------------------------------------------------- */

ini_set('error_log', './logs/error.log');

date_default_timezone_set('Asia/Tehran');

/* -------------------------------------------------------------------------- */

if (!isset($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'])) {
    die();
}

if (!extension_loaded('nd_pdo_mysql')) {
    throw new Exception('Extension ( nd_pdo_mysql ) is required !');
}

/* -------------------------------------------------------------------------- */

require './vendor/autoload.php';

$config = require('./config.php');
$strings = require('./strings.php');

require './funcs.php';

/* -------------------------------------------------------------------------- */

filter_input(INPUT_SERVER, 'HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN', FILTER_SANITIZE_SPECIAL_CHARS);
$secretToken = $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'];
if ($secretToken !== appConfig('bot.secret_token')) {
    error_log("Illegal access with secret_token: {$secretToken} and ip: {$_SERVER['REMOTE_ADDR']} !");
    die();
}

/* -------------------------------------------------------------------------- */

$capsule = new Manager();
$capsule->addConnection(appConfig('db'));
$capsule->setAsGlobal();
$capsule->bootEloquent();

/* -------------------------------------------------------------------------- */

$cache = new Psr16Cache(new FilesystemAdapter());

$nutgramConfig = [
    'timeout'  => 2,
    'cache' => $cache,
    'client' => [
        'proxy' => '127.0.0.1:2081',
    ]
];

$bot = new Nutgram(appConfig('bot.token'), $nutgramConfig);
$bot->setRunningMode(Webhook::class);

/* -------------------------------------------------------------------------- */

$bot->middleware(CheckChatTypeMiddleware::class);
$bot->middleware(CheckUpdateTypeMiddleware::class);
$bot->middleware(CollectChatMiddleware::class);

/* -------------------------------------------------------------------------- */

$bot->onCommand('start', StartCommand::class);

$bot->onText(appStr('btn.stat'), StatCommand::class)->middleware(AdminsOnlyMiddleware::class);

$bot->onText(appStr('btn.messaging'), MessagingConverstaion::class)->middleware(UsersOnlyMiddleware::class);

$bot->onText(appStr('btn.back'), BackCommand::class);

/* -------------------------------------------------------------------------- */

$bot->fallbackOn(UpdateTypes::MESSAGE, FallbackOnMessageHandler::class);

/* -------------------------------------------------------------------------- */

$bot->onException(BotException::class, [OnExceptionHandler::class, 'bot']);

$bot->onException(OnExceptionHandler::class);

/* -------------------------------------------------------------------------- */

$bot->onApiError(".*(user not found).*", [OnApiErrorHandler::class, 'doNothing']);

$bot->onApiError(OnApiErrorHandler::class);

/* -------------------------------------------------------------------------- */

$bot->run();

/* -------------------------------------------------------------------------- */
