<?php

use Framework\Routing\Route;
use Framework\Http\Response;

return array(
	new Route(array(
		'name' => 'testmodule_index',
		'path' => '/',
		'controller' => function () {
			return new Response('Hello from index controller!');
		}
	))
);