<?php

namespace Cart\Basket\Exceptions;

use Exception;

class QuantityDrainedException extends Exception
{
	protected $message = "Out of stock!";
}