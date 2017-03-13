<?php 

namespace Cart\Events;

use Cart\Handlers\Contracts\HandlersInterface;

class Event 
{
	protected $handlers = [];

	public function attach($handlers)
	{
		if (is_array($handlers)) {
			foreach ($handlers as $handler) {
				if (!$handler instanceof HandlersInterface) {
					continue;
				}

				$this->handlers[] = $handler;
			}

			return;
		}

		if (!$handlers instanceof HandlersInterface) {
			return;
		}

		$this->handlers[] = $handlers;
	}

	public function dispatch() {
		foreach ($this->handlers as $handler) {
			$handler->handle($this);
		}
	}
}