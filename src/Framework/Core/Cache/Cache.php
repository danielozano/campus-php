<?php

namespace Framework\Core\Cache;

use Framework\Core\Cache\CacheItem;
/**
 * Adaptador
 */
// NOTE: en desarrollo
class Cache
{
	private $namespace = __NAMESPACE__;
	private $cacheSystem;

	public function __construct($type = 'filesystem')
	{
		$type = ucfirst($type);
		$type .= 'Cache';
		$type = $this->namespace . '\\' .$type;
		$this->cacheSystem = new $type;
	}

	public function save($key, $value, $ttl)
	{
		$item = new CacheItem();
		$item->setKey($key);
		$item->set($value);
		// TODO: crear datetime para ttl, falta sumar ahora + tiempo de vida
		$item->expiresAt($ttl);

		$this->cacheSystem->save($item);
	}

	public function get($key)
	{
		return $this->cacheSystem->getItem($key);
	}

	public function delete($key)
	{
		if ($this->cacheSystem->hasItem($key)) {

			$this->cacheSystem->deleteItem($key);

			return true;
		}

		return false;
	}
}