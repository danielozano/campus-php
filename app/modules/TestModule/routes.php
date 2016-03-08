<?php

use Framework\Routing\Route;

return array(
	new Route(array(
		'name' => 'TestController1',
		'controller' => 'TestModule\Controller\TestController:bye',
		'path' => '/bye/{a}'
	))
);