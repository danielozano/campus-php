<?php
namespace Framework\Core\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class FilesystemCache implements CacheItemPoolInterface
{
	private $cachePool = array();

	private $rootDir;

	public function __construct($rootDir = null)
	{
		// TODO: hardcoded por motivos de desarrollo refactorizar
		$config = \App::getConfig();
		$this->rootDir = $config['cache_dir'];
		if (!is_dir($this->rootDir)) {
			mkdir(dirname($this->rootDir));
		}
	}

	/**
	 * Al ser filesystem buscar fichero.
	 * Devolver un objeto CacheItemInterface.
	 * 
	 * @param  [type] $key [description]
	 * @return CacheItemInterface
	 */
	public function getItem($key)
	{
		if (!$this->hasItem($key)) {
			return false;
		}

		$filename = $this->getFileName($key);

		// si hay caché obtener contenido.
		$item = file_get_contents($filename);

		$item = @unserialize($item);

		if (!$item) {
			unlink($filename);
		}

		/*if (time() > $item->getTtl()) {
			unlink($filename);
			return false;
		}*/
		return $item;
	}

	/**
	 * Obtener el nombre del fichero a partir de su clave, y la
	 * ruta por defecto.
	 * 
	 * @param  [type] $key     [description]
	 * @param  [type] $rootDir [description]
	 * @return [type]          [description]
	 */
	public function getFileName($key, $rootDir = null)
	{
		if (!$rootDir) {
			$rootDir = $this->rootDir;
		}

		return $rootDir . DIRECTORY_SEPARATOR  . $key;
	}

	public function getItems(array $keys = array()) {}

	public function hasItem($key)
	{
		$filename = $this->getFileName($key);

		if (!file_exists($filename) ||
			!is_readable($filename)
		 ) {
			return false;
		}

		return true;
	}

	public function clear() {}

	public function deleteItem($key)
	{
		$filename = $this->getFileName($key);
		if (!file_exists($filename))
		{
			return false;
		}
		unlink($filename);
		return true;
	}

	public function deleteItems(array $keys) {}

	public function save(CacheItemInterface $item)
	{
		// La clave es el nombre, por lo que a partir del nombre obtenemos el
		// contenido
		$file = fopen($this->getFileName($item->getKey()), 'w');

		$data = serialize($item);

		if (fwrite($file, $data) === false) {
			// exception
			return false;
		}
		fclose($file);
		return true;

	}

	public function saveDeferred(CacheItemInterface $item) {}

	public function commit() {}
}