<?php

namespace Cart\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cart\Validation\Contracts\ValidatorInterface;
use Cart\Models\Product;
use Slim\Views\Twig;
use Cart\Basket\Basket;
use Slim\Router;
use Cart\Validation\Form\OrderForm;
use Cart\Models\Customer;
use Cart\Models\Address;
use Braintree_Transaction;


class OrderController
{
	protected $basket;
	protected $router;
	protected $validator;

	public function __construct(Basket $basket, Router $router, ValidatorInterface $validator)
	{
		$this->basket = $basket;
		$this->router = $router;
		$this->validator = $validator;
	}

	public function index(Request $request, Response $response, Twig $view)
	{
		$this->basket->refresh();

		if(!$this->basket->subTotal()) {
			return $response->withRedirect($router->pathFor('cart.index'));
		}

		$view->render($response, "order/index.twig");
	}

	public function create(Request $request, Response $response)
	{
		$this->basket->refresh();

		if(!$this->basket->subTotal()) {
			return $response->withRedirect($this->router->pathFor('cart.index'));
		}

		if(!$request->getParam('payment_method_nonce')) {
			return $response->withRedirect($this->router->pathFor('order.index'));
		}

		// $validation = $this->validator->validate($request, OrderForm::rules());

		// if ($validation->fails()) {
		// 	return $response->withRedirect($this->router->pathFor('order.index'));
		// }

		$hash = bin2hex(random_bytes(32));

		$customer = Customer::firstOrCreate([
			'email' => $request->getParam('email'),
			'name' => $request->getParam('name')
		]);

		$address = Address::firstOrCreate([
			'address1' => $request->getParam('address1'),
			'address2' => $request->getParam('address2'),
			'city' => $request->getParam('city'),
			'postal_code' => $request->getParam('postal_code')
		]);	

		$order = $customer->orders()->create([
			'hash' => $hash,
			'total' => $this->basket->subTotal() + 5,
			'paid' => false,
			'address_id' => $address->id
		]);

		$order->products()->saveMany(
			$this->basket->all(),
			$this->getQuantity($this->basket->all())
		);

		$result = Braintree_Transaction::sale([
			'amount' => $this->basket->subTotal(),
			'paymentMethodNonce' => $request->getParam('payment_method_nonce'),
			'options' => [
				'submitForSettlement' => True
			]
		]);

		die(var_dump($result));
	}

	protected function getQuantity($items)
	{
		$quantities = [];

		foreach($items as $item) {
			$quantities[] = ['quantity' => $item->quantity];
		}

		return $quantities;
	}
}