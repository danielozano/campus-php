<?php
namespace Framework\Core\Cache;

interface CacheInterface
{
	public function fetch($key);
	public function store($key, $data, $ttl);
	public function delete($key);
	public function clear();
	public function exists($key);
}