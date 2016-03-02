<?php

use Framework\Routing\RouteCollection;
use Framework\Routing\Route;

$routeCollection = new RouteCollection();

$routeCollection->add(new Route(
	array(
		'name' => 'ruta1',
		'path' => '/hello/{name}',
		'controller' => function($name = 'World') {
			return new Framework\Http\Response("Hello $name");
		}
	)
));

return $routeCollection;