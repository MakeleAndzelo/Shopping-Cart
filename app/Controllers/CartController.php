<?php

namespace Cart\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Cart\Models\Product;
use Cart\Basket\Basket;
use Slim\Router;

class CartController
{
	protected $product;
	protected $basket;

	public function __construct(Product $product, Basket $basket)
	{
		$this->product = $product;
		$this->basket = $basket;
	}

	public function index(Response $response, Request $request, Twig $view)
	{

		return $view->render($response, 'cart/index.twig');
	}

	public function add($slug, $quantity, Response $response, Request $request, Router $router)
	{
		$product = $this->product->where('slug', $slug)->first();

		if (!$product) {
			return $response->withRedirect($router->pathFor('home'));
		}

		try {
			$this->basket->add($product, $quantity);
		} catch (QuantityDrainedException $e) {
			//
		}

		return $response->withRedirect($router->pathFor('cart.index'));
	}
}