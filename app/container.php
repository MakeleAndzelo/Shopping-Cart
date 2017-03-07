<?php

use function DI\get;
use Slim\Views\Twig;
use Interop\Container\ContainerInterface;
use Cart\Models\Product;
use Slim\Router;
use Slim\Views\TwigExtension;

return [
	'router' => get(Router::class),
	Twig::class => function(ContainerInterface $c) {
		$twig = new Twig(__DIR__ . '/../resources/views', [
			'cache' => false
		]);

		$twig->addExtension(new TwigExtension(
			$c->get('router'),
			$c->get('request')->getUri()
		));

		return $twig;
	},
	Product::class => function() {
		return new Product;
	}
];