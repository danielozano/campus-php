<?php
namespace Framework\Core\Cache\Drivers;

use Framework\Core\Cache\CacheInterface;

class Filesystem implements CacheInterface
{
	private $cachePool = array();
	private $cacheDir;

	public function store($key, $value, $ttl)
	{

	}

	public function fetch($key)
	{

	}

	public function clear()
	{

	}
	public function delete($key)
	{

	}
	public function exists($key)
	{

	}
}