<?php

use function DI\get;
use Slim\Views\Twig;
use Interop\Container\ContainerInterface;
use Cart\Models\Product;
use Slim\Router;
use Slim\Views\TwigExtension;
use Cart\Support\Storage\Contracts\StorageInterface;
use Cart\Support\Storage\SessionStorage;
use Cart\Basket\Basket;

return [
	'router' => get(Router::class),
	StorageInterface::class => function(ContainerInterface $c) {
		return new SessionStorage('cart');
	},
	Basket::class => function(ContainerInterface $c) {
		return new Basket(
			$c->get(SessionStorage::class),
			$c->get(Product::class)
		);
	},
	Twig::class => function(ContainerInterface $c) {
		$twig = new Twig(__DIR__ . '/../resources/views', [
			'cache' => false
		]);

		$twig->addExtension(new TwigExtension(
			$c->get('router'),
			$c->get('request')->getUri()
		));

		$twig->getEnvironment()->addGlobal('basket', $c->get(Basket::class));

		return $twig;
	},
	Product::class => function() {
		return new Product;
	}
];