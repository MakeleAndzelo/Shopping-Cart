<?php

namespace Cart\Handlers;

use Cart\Handlers\Contracts\HandlersInterface;

class UpdateStock implements HandlersInterface
{
	public function handle($event)
	{
		foreach ($event->basket->all() as $product) {
			$product->decrement('stock', $product->quantity);
		}	
	}
}