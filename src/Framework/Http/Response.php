<?php
/**
 * @author Daniel Lozano Morales <dn.lozano.m@gmail.com>
 */

namespace Framework\Http;

/**
 * Esta clase funcionará como la representación de una respuesta devuelta por un servidor web.
 */
class Response
{
	/**
	 * Contenido de la respuesta
	 * 
	 * @var string
	 */
	private $content;

	/**
	 * Código de estado de la respuesta, 200 por defecto
	 * 
	 * @var string
	 */
	private $statusCode;

	/**
	 * Texto que acompaña al código de estado
	 * 
	 * @var string
	 */
	private $statusText;

	/**
	 * Versión del protocolo http
	 * 
	 * @var string
	 */
	private $protocolVersion = 'HTTP/1.1';

	/**
	 * Array asociativo para almacenar las cabeceras de la respuesta
	 * Debe almacenarse de la siguiente forma:
	 * array('Header-Key' => 'Header value');
	 * 
	 * @var array
	 */
	private $headers;

	private $cookies = array();

	public function __construct($content = null, $statusCode = 200, $headers = array())
	{
		$this->setContent($content);
		$this->headers = $headers;
	}

	/**
	 * Establecer el contenido de la respuesta
	 * 
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * Obtener el contenido de la respuesta
	 * 
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Establecer el código de estado de la respuesta, y su texto.
	 * El texto es opcional.
	 * TODO: validar código de estado, y asignar texto automáticamente.
	 * 
	 * @param int $code
	 * @param string $text
	 */
	public function setStatusCode($code, $text = '')
	{
		$this->statusCode = $code;
		$this->statusText = $text;
	}

	/**
	 * Enviar respuesta, tanto cabeceras como contenido.
	 * 
	 * @return [type] [description]
	 */
	public function sendResponse()
	{
		$this->sendHeaders();
		$this->sendContent();
		
		return $this;
	}

	private function sendContent()
	{
		echo $this->content;
	}

	private function sendHeaders()
	{
		// Comprobar que no se han enviado las cabeceras
		if (headers_sent()) {
			return;
		}
		// Establecer las cabeceras
		foreach ($this->headers as $name => $value) {
			header($name . ': ' . $value);
		}

		// Establecer el código de estado
		// TODO: compatibilidad con fast-cgi
		header(sprintf('%s %s %s', $this->protocolVersion, $this->statusCode, $this->statusText));

		// Establecer las cookies
		foreach ($this->cookies as $name => $value) {
			// setcookie()
		}
	}
}