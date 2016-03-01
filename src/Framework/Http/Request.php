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
	private $headers;

	private $query;

	private $post;

	private $cookies;

	private $session;

	private $files;


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

	public function getPathInfo()
	{
		$path = '';
		if (isset($this->server['REQUEST_URI'])) {
			$path = $this->server['REQUEST_URI'];
		}

		return $path;
	}
}