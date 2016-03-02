<?php
/**
 * @author  Daniel Lozano Morales <dn.lozano.m@gmail.com>
 */
namespace Framework\Routing;
/**
 * TODO: hacer obligatorios las opciones: path, name y controller.
 * TODO: Generar regex con restricciones (decimal, string)
 * TODO: arreglar regex para que se admita un tailing slash, ejemplo: /hola/{name} resuelva a: /hola y /hola/daniel. Debería coincidir con /hola, /hola/ y /hola/daniel
 */
/**
 * La funcionalidad de esta clase es otorgar abstracción a una entidad Ruta. Es decir poder manipular
 * la ruta como si se tratara de un objeto, pudiendo así interactuar con otras clases de forma fácil.
 * Simplemente debe almacenar información básica de una ruta, y métodos para poder establecer o acceder
 * a dicha información.
 */
class Route
{
	/**
	 * Opciones de la ruta
	 * 
	 * @var array
	 */
	private $options;

	/**
	 * Valores de los parámetros de la ruta
	 * 
	 * @var array
	 */
	private $parameters;

	/**
	 * Controlador de la ruta
	 *
	 * @var  string
	 */
	protected $controller;

	/**
	 * Método a llamar de la ruta
	 * 
	 * @var  string
	 */
	protected $method;

	public function __construct($options = array(), $parameters = array())
	{
		$this->options = $options;
		$this->parameters = $parameters;
	}

	/**
	 * Devuelve un array con los parámetros de la ruta
	 * 
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * Devuelve un array con las opciones de la ruta
	 * 
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Devuelve el valor de una opción a partir de su nombre
	 * 
	 * @param  string $name
	 * @return mixed
	 */
	public function getOption($name)
	{
		return $this->options[$name];
	}

	/**
	 * Devuelve el nombre de la ruta
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->getOption('name');
	}

	/**
	 * Devuelve el valor del controlador
	 * 
	 * @return string
	 */
	public function getController()
	{
		return $this->getOption('controller');
	}

	/**
	 * Devuelve el método
	 * 
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Devuelve el path de la ruta
	 * 
	 * @return string
	 */
	public function getPath()
	{
		return $this->getOption('path');
	}
	
	/**
	 * Crear expresión regular para esta ruta.
	 * 
	 * @param  string $path
	 */
	public function generateRegex()
	{
		$path = $this->getPath();
		$regex = '';
		$varRegex = '';
		// primero obtener los parámetros de la URL. Deberían haber sido indicados mediante corchetes. Por ejemplo: /route/post/{id}
		$matches = array();
		preg_match_all('#\{\w+\}#', $path, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		foreach ($matches as $match) {
			$varRegex .= '(/[^/]+)';
			//$varRegex .= '(?:/\w+)';
		}
		// Obtener string antes de la primera variable en la ruta, es decir la parte estática de la ruta. Para ello necesitamos la posición en el string de la primera variable, y restarle uno ya que no deseamos que se incluya el slash /, porque ya está incluido dentro de la regex generada anteriormente.
		if (count($matches) > 0) {
			$pos = $matches[0][0][1];			
			$staticPath = substr($path, 0, $pos - 1);
		} else {
			$staticPath = $path;
		}
		// Juntar la parte estática con la dinámica.
		$regex = '#^' . $staticPath;
		if ('' !== $varRegex) {
			$regex .= $varRegex;
		}
		$regex .= '$#s';

		return $regex;
	}

	/**
	 * Para devolver los argumentos necesitamos la ruta actual y el
	 * pattern
	 * 
	 * @return array
	 */
	public function getRouteArguments($pattern, $request)
	{
		/**
		 * Obtener todos los argumentos incluidos en el patrón de la ruta. Estos irán entre
		 * corchetes {arg}. Obtendremos un array en el cual el primero elemento contendrá el
		 * resultado de la regex y en segundo su posición dentro del string.
		 */
		preg_match_all('#\{\w+\}#', $pattern, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

		$arguments = array();

		$varRegex = '';

		// recortar para obtener la parte estática
		foreach ($matches as $match) {
			// Nombre de la variable obtenida del pattern
			$varName = substr($match[0][0], 1, -1);	
			$varRegex .= "\/(?P<$varName>[^/]+)?";
		}
		// La parte estática desde el inicio hasta el inicio del primer parámetro, sin el slash final
		$staticPath = substr($pattern, 0, $matches[0][0][1] - 1);
		
		// Generar expresión regular
		$regex = "~^" . $staticPath . $varRegex . "~";

		// Obtener los argumentos de la expresión regular
		preg_match($regex, $request, $arguments);

		return $arguments;
	}
}