<?php

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Router;
use Framework\Routing\Matcher;
/**
 * TODO: añadir archivos de configuración
 * TODO: añadir sistema de template en php
 * TODO: añadir carga de módulos.
 * TODO: implementar entornos: dev y prod.
 * TODO: impelementar librerías globales.
 * TODO: implementar Registry para almacenar servicios que estarán disponibles globalmente.
 */
class App
{
	private $enviroment;

	public function __construct($enviroment = 'prod')
	{
		$this->enviroment = $enviroment;
	}

	/**
	 * Ejecutar aplicación
	 * 
	 * @param  Request $request
	 */
	public function run(Request $request)
	{
		$routeCollection = include_once 'config/routes.php';
		$matcher = new Matcher($routeCollection);
		$router = new Router($matcher);
		$response = $router->handle($request->getPathInfo());

		// Forzar que todos los controladores devuelvan un objeto Response
		if (!$response instanceof Response) {
			throw new \InvalidArgumentException('The controller must return a Response object');
		}
		$response->sendResponse();
	}
}