<?php

namespace Framework\Core\Cache;

use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
	public function getKey() {}

	public function get() {}

	public function isHit() {}

	public function set($value) {}

	public function expiresAt($expiration) {}

	public function expiresAfter($time) {}
}