<?php

use Cart\App;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager as Capsule;
use Braintree_Configuration;
use Dotenv\Dotenv;


session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new App;

$container = $app->getContainer();

$dotenv = new Dotenv(__DIR__ . "/../");
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
	'driver' => $_ENV["DB_DRIVER"],
	'host' => $_ENV["DB_HOST"],
	'database' => $_ENV["DB_DATABASE"],
	'username' => $_ENV["DB_USERNAME"],
	'password' => $_ENV["DB_PASSWORD"],
	'charset' => $_ENV["DB_CHARSET"],
	'collation' => $_ENV["DB_COLLATION"],
	'prefix' => $_ENV["DB_PREFIX"]
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

Braintree_Configuration::environment($_ENV['BT_ENVIRONMENT']);
Braintree_Configuration::merchantId($_ENV['BT_MERCHANT_ID']);
Braintree_Configuration::publicKey($_ENV['BT_PUBLIC_KEY']);
Braintree_Configuration::privateKey($_ENV['BT_PRIVATE_KEY']);

require __DIR__ . '/../app/routes.php';

$app->add(new \Cart\Middleware\ValidationErrorsMiddleware($container->get(Twig::class)));
$app->add(new \Cart\Middleware\OldInputsMiddleware($container->get(Twig::class)));