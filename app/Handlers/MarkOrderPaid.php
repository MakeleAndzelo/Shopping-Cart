<?php

namespace Cart\Handlers;

use Cart\Handlers\Contracts\HandlersInterface;

class MarkOrderPaid implements HandlersInterface
{
	public function handle($event)
	{
		$event->order->update([
			'paid' => true,
		]);		
	}
}