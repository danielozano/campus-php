<?php
/**
 * @author Daniel Lozano Morales <dn.lozano.m@gmail.com>
 */
namespace Framework\Http;
/**
 * Esta clase permitirá crear una petición desde cero, o a partir de la información de la petición actual.
 */
class Request
{
	/**
	 * TODO: ¿implementar cabeceras? Habría que destripar $_SERVER
	 * 
	 * @var array
	 */
	private $headers;

	/**
	 * Parámetros $_GET
	 * 
	 * @var array
	 */
	private $query;

	/**
	 * Parámetros $_POST
	 * 
	 * @var array
	 */
	private $post;

	/**
	 * Parámetros $_COOKIE
	 * 
	 * @var array
	 */
	private $cookies;

	/**
	 * Parámetros $_SESSION
	 * NOTE: aún no está clara su implementación en esta clase
	 * 
	 * @var array
	 */
	private $session;

	/**
	 * Parámetros $_FILES
	 * 
	 * @var array
	 */
	private $files;

	/**
	 * Constructor
	 * 
	 * @param array $query   Array asociativo, contiene $_GET
	 * @param array $post    Array asociativo, contiene $_POST
	 * @param array $cookies Array asociativo, contiene $_COOKIE
	 * @param array $files   Array asociativo, contiene $_FILES
	 * @param array $server  Array asociativo, contiene $_SERVER
	 * @param array $session Array asociativo, contiene $_SESSION
	 */
	public function __construct($query = array(), $post = array(), $cookies = array(), $files = array(), $server = array(), $session = array()) 
	{
		$this->query = $query;
		$this->post = $post;
		$this->cookies = $cookies;
		$this->files = $files;
		$this->server = $server;
		$this->session = $session;
	}

	/**
	 * Crea petición a partir de las variables PHP globales
	 * 
	 * @return  Framework\Http\Request
	 */
	public static function createFromGlobals()
	{
		$session = array();
		if (isset($_SESSION)) {
			$session = $_SESSION;
		}
		return new self($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER, $session);
	}

	/**
	 * Obtiene la URI contenida en la petición actual.
	 * 
	 * @return string
	 */
	public function getPathInfo()
	{
		$path = '';
		if (isset($this->server['REQUEST_URI'])) {
			$path = $this->server['REQUEST_URI'];
		}

		return $path;
	}
}