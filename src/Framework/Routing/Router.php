<?php
// src/Framework/Routing/Router.php

namespace Framework\Routing;
/**
 * Necesito: los parámetros de la petición actual, un matcher.
 */
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

	public function __construct($matcher, $routeResolver)
	{
		$this->matcher = $matcher;
	}

	public function handle($request)
	{

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