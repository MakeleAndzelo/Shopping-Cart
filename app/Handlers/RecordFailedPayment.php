<?php

namespace Cart\Handlers;

use Cart\Handlers\Contracts\HandlersInterface;

class RecordFailedPayment implements HandlersInterface
{
	public function handle($event)
	{
		$event->order->payment()->create([
			'failed' => true
		]);		
	}
}