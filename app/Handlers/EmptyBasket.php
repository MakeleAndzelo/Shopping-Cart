<?php

namespace Cart\Handlers;

use Cart\Handlers\Contracts\HandlersInterface;

class EmptyBasket implements HandlersInterface
{
	public function handle($event)
	{
		$event->basket->clear();
	}
}