<?php 

namespace Cart\Support\Storage\Contracts;

interface StorageInterface
{
	public function set($index, $value);
	public function get($index);
	public function all();
	public function exists($index);
	public function unset($index);
	public function clear();
}