<?php

namespace Framework\Core\Cache;

use Psr\Cache\CacheItemInterface;
/**
 * Cada objeto CacheItemInterface debe ser generado y devuelvo por CacheItemPoolInterface
 */
class CacheItem implements CacheItemInterface
{
	private $key;
	private $value;
	public function getKey() 
	{
		return $this->key;
	}

	public function setKey($key)
	{
		$this->key = $key;
	}
	
	/**
	 * Obtener el valor almacenado en caché para una clave
	 * en concreto.
	 * 
	 * Debe devoler null si isHit devuelve false
	 * 
	 * @return [type] [description]
	 */
	public function get()
	{
		return $this->value;
	}

	/**
	 * Comprueba si hay coincidencia en caché o no.
	 * 
	 * @return boolean 
	 */
	public function isHit(){}

	/**
	 * Establecer el valor para una clave en concreto.
	 * 
	 * @param [type] $value [description]
	 */
	public function set($value)
	{
		$this->value = $value;
	}

	public function expiresAt($expiration)
	{

	}

	public function expiresAfter($time) {}
}