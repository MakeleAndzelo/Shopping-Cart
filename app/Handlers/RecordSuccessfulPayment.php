<?php

namespace Cart\Handlers;

use Cart\Handlers\Contracts\HandlersInterface;

class RecordSuccessfulPayment implements HandlersInterface
{
	protected $transactionId;

	public function __construct($transactionId)
	{
		$this->transactionId = $transactionId;
	}

	public function handle($event)
	{
		$event->order->payment()->create([
			'failed' => false,
			'transaction_id' => $this->transactionId,
		]);
	}
}