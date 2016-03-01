<?php

use Framework\Http\Request;
use Framework\Routing\Router;
use Framework\Routing\Matcher;

class App
{
	private $enviroment;

	public function __construct($enviroment = 'prod')
	{
		$this->enviroment = $enviroment;
	}

	public function run(Request $request)
	{
		$routeCollection = include_once 'config/routes.php';
		$matcher = new Matcher($routeCollection);
		$router = new Router($matcher);
		$router->handle($request->getPathInfo());

	}
}