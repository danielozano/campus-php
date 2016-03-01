<?php
// src/Framework/Routing/Router.php
/**
 * @author Daniel Lozano Morales <dn.lozano.m@gmail.com>
 */
namespace Framework\Routing;

/**
 * La responsabilidad de esta clase consiste en proporcionar una API a partir de la cual
 * poder conectar las clases de la librería Framework\Routing. Su misión es a partir de objetos
 * pertenecientes a dichas clases ser capaz de dirigir el flujo de la aplicación a un controlador.
 * A partir de la ruta actual y un matcher podrá hacer una llamada dinámica a un controlador. Para 
 * pasar parámetros a dicho controlador quizás se necesite una clase más (algo así como un Resolver)
 */
class Router implements RouterInterface
{
	/**
	 * Objeto para buscar rutas coincidentes
	 * 
	 * @var Framework\Routing\Matcher
	 */
	private $matcher;

	/**
	 * Objeto para resolver el controlador y sus argumentos
	 * 
	 * @var Framework\Routing\ControllerResolver
	 */
	private $resolver;

	/**
	 * Constructor
	 * 
	 * @param Matcher $matcher
	 */
	public function __construct(Matcher $matcher)
	{
		$this->matcher = $matcher;
	}

	/**
	 * Maneja la petición actual, redirigiendo el flujo de la aplicación
	 * en función de las rutas definidas
	 * 
	 * @param  string $request
	 */
	public function handle($request)
	{
		// Comprobar si la petición actual existe y obtener los parámetros.
		$parameters = $this->matcher->match($request);
		
		if (!$parameters) {
			// TODO: mejorar respuesta 404, die es muy sucio.
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
			die('Not Found');
		}
		/**
		 * Nos permitirá resolver el controlador y sus argumentos
		 * 
		 * @var Framework\Routing\ControllerResolver
		 */
		$resolver = new ControllerResolver();
		
		$controller = $resolver->getController($parameters);

		/**
		 * Parámetros obtenidos de la ruta que debemos pasar al contorlador
		 * 
		 * @var array
		 */
		$arguments = $resolver->getArguments($parameters, $controller);
		
		// Llamada dinámica
		call_user_func_array($controller, $arguments);
	}

	/**
	 * Devuelve el matcher
	 * 
	 * @return Framework\Routing\Matcher
	 */
	public function getMatcher()
	{
		return $this->matcher;
	}

	/**
	 * Establece el matcher
	 * 
	 * @param Framework\Routing\Matcher $matcher
	 */
	public function setMatcher(Matcher $matcher)
	{
		$this->matcher = $matcher;
	}
}