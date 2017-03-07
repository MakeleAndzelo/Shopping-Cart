<?php

namespace Cart\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cart\Models\Product;
use Slim\Views\Twig;

class HomeController
{
	public function index(Request $request, Response $response, Twig $view, Product $product)
	{
		$products = $product->get();

		die(var_dump($products->first()->title));

		return $view->render($response, 'home.twig');
	}
}