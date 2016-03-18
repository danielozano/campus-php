<?php
namespace Framework\Core\Cache\Drivers;

use Framework\Core\Cache\CacheInterface;

class Filesystem implements CacheInterface
{
	private static $cachePool = array();
	private $cacheDir;

	public function __construct()
	{
		//$this->cacheDir = ini_get('session.save_path') . DIRECTORY_SEPARATOR . 'custom_cache';
		// TODO: hardcoded modificar
		$config = \App::getConfig();
		$this->setCacheDir($config['cache_dir']);
	}

	/**
	 * Establecer el directorio de cache
	 * TODO: establecer uno válido por defecto, checkear permisos vagrant da problemas.
	 * 
	 * @param string $cacheDir
	 */
	public function setCacheDir($cacheDir)
	{
		if (!is_dir($cacheDir)) {
			throw new \Exception("$cacheDir no es un directorio", 1);
		}
		$this->cacheDir = $cacheDir;
	}

	/**
	 * Almacenar valor en caché
	 * 
	 * @param  string $key
	 * @param  mixed $value
	 * @param  int $ttl
	 * @return [type]        [description]
	 */
	public function store($key, $value, $ttl)
	{
		if ($this->exists($key)) {
			return false;
		}

		$file = fopen($this->getFileName($key), 'a+');

		if (!$file) {
			throw new \Exception("No se puede escribir en cache, " . $file, 1);
		}
		// abrir archivo en modo exclusivo, por si se intenta abrir el mismo varias veces
		flock($file, LOCK_EX);
		fseek($file, 0);
		ftruncate($file, 0);

		$data = serialize(array(time() + $ttl, $value));

		if (false === fwrite($file, $data)) {
			throw new Exception("Error al intentar escribir en el fichero " . $file, 1);
		}

		fclose($file);

		self::$cachePool[$key] = $data;

		return true;
	}

	/**
	 * Obtener elemento de la caché.
	 * 
	 * @param  strin $key
	 * @return mixed
	 */
	public function fetch($key)
	{
		if (!$this->exists($key)) {
			return false;
		}

		$filename = $this->getFileName($key);

		if (!file_exists($filename)) {
			return false;
		}

		$file = fopen($filename, 'r');

		if (!$file) {
			return false;
		}

		flock($file, LOCK_SH);

		$data = file_get_contents($filename);

		fclose($file);

		$data = @unserialize($data);

		if (!$data) {
			unlink($filename);
			return false;
		}

		if (time() > $data[0]) {
			unlink($filename);
			return false;
		}

		return $data[1];
	}

	/**
	 * Limpiar todos los elementos de la caché
	 * 
	 * @return bool
	 */
	public function clear()
	{
		foreach (self::$cachePool as $key => $value) {
			$this->delete($key);
		}
		return true;
	}

	/**
	 * Borrar elemento de caché
	 * 
	 * @param  string $key
	 * @return bool
	 */
	public function delete($key)
	{
		if (!$this->exists($key)) {
			return false;
		}

		$file = $this->getFileName($key);

		return unlink($file);
	}

	/**
	 * Comprobar si existe un elemento en caché
	 * 
	 * @param  string $key
	 * @return bool
	 */
	public function exists($key)
	{
		if (file_exists($this->getfileName($key))) {
			return true;
		}
		
		return false;
	}

	/**
	 * Obtener la ruta del archivo en el que almacenar
	 * la caché
	 * 
	 * @param  string $key
	 * @return string
	 */
	public function getFileName($key)
	{
		$key = md5($key);
		return $this->cacheDir . DIRECTORY_SEPARATOR . $key;
	}
}