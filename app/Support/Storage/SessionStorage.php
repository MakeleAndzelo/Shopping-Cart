<?php 

namespace Cart\Support\Storage;

use Cart\Support\Storage\Contracts\StorageInterface;
use Countable;

class SessionStorage implements StorageInterface, Countable
{
	protected $bucket;

	public function __construct($bucket = "default")
	{
		if (!isset($_SESSION[$bucket])) {
			$_SESSION[$bucket] = [];
		}

		$this->bucket = $bucket;
	}

	public function set($index, $value)
	{
		$_SESSION[$this->bucket][$index] = $value;
	}

	public function get($index)
	{
		if (!$this->exists($index)) {
			return NULL;
		}

		return $_SESSION[$this->bucket][$index];
	}

	public function all()
	{
		return $_SESSION[$this->bucket];
	}

	public function exists($index)
	{
		return isset($_SESSION[$this->bucket][$index]);
	}

	public function unset($index)
	{
		if ($this->exists($index)) {
			unset($_SESSION[$this->bucket][$index]);
		}
	}

	public function clear()
	{
		unset($_SESSION[$this->bucket]);
	}

	public function count()
	{
		return count($this->all());
	}
}