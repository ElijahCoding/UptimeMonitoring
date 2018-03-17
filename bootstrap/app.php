<?php

use App\Models\Endpoint;
use App\Console\Commands\Run;
use App\Console\Commands\StatusCommand;
use App\Console\Commands\AddEndpointCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => getenv('APP_DEBUG') === 'true',

        'app' => [
            'name' => getenv('APP_NAME')
        ],

        'views' => [
            'cache' => getenv('VIEW_CACHE_DISABLED') === 'true' ? false : __DIR__ . '/../storage/views'
        ],

        'db' => [
            'driver' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'port' => getenv('DB_PORT'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]
    ],
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);

$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['dispatcher'] = function () {
  $dispatcher = new EventDispatcher();

  // Add listeners

  return $dispatcher;
};

$container['guzzle'] = function () {
  return new GuzzleHttp\Client();
};

$container['console'] = function ($container) {
  $application = new Application();

  $application->add(new AddEndpointCommand());
  $application->add(new StatusCommand());

  $application->add(
    new Run(
      $container->guzzle
    )
  );

  return $application;
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => $container->settings['views']['cache']
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

require_once __DIR__ . '/../routes/web.php';
