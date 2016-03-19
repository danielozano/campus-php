<?php
namespace Framework\Core\Cache;

class Cache implements CacheInterface
{
	private $cacheSystem;

	public function __construct($system = 'filesystem')
	{
		$namespace = __NAMESPACE__;
		$className = $namespace . '\\Drivers\\' . ucfirst($system);

		$this->cacheSystem = new $className;
	}

	public function store($key, $data, $ttl = 3600)
	{
		return $this->cacheSystem->store($key, $data, $ttl);
	}

	public function fetch($key)
	{
		return $this->cacheSystem->fetch($key);
	}

	public function delete($key)
	{
		return $this->cacheSystem->delete($key);
	}

	public function exists($key)
	{
		return $this->cacheSystem->exists($key);
	}
	public function clear()
	{
		return $this->cacheSystem->clear();
	}
	public function createKey($key)
	{
		if (is_array($key)) {

		} else {
			return md5($key);
		}
	}
}