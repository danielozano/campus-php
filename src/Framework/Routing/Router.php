<?php
// src/Framework/Routing/Router.php

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
	private $matcher;
	private $resolver;

	public function __construct(Matcher $matcher)
	{
		$this->matcher = $matcher;
	}

	/**
	 * Función principal de la clase, será la que compruebe si hay coincidencias
	 * para la petición actual, y 
	 * @param  [type] $request [description]
	 * @return [type]          [description]
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
		$route = $parameters['route'];
		// necesito hacer llamada a una función de una clase
		// Controlador a ejecutar de la ruta matcheada
		$resolver = new ControllerResolver();
		
		$controller = $resolver->getController($parameters);

		// Parámetros ordenados gracias a Reflection
		$arguments = $resolver->getArguments($parameters, $controller);
		
		// Llamada dinámica
		call_user_func_array($controller, $arguments);
	}

	public function getMatcher()
	{
		return $this->matcher;
	}

	public function setMatcher($matcher)
	{
		$this->matcher = $matcher;
	}
}