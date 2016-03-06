<?php
/**
 * TODO: cargar aquí las rutas de cada módulo, así podrán definirse rutas de dos formas diferentes.
 */
use Framework\Routing\RouteCollection;
use Framework\Routing\Route;

$routeCollection = new RouteCollection();

$routeCollection->add(new Route(
	array(
		'name' => 'ruta1',
		'path' => '/hello/{name}',
		'controller' => function($name = 'World') {
			return new Framework\Http\Response("Hello $name", 200);
		}
	)
));

return $routeCollection;