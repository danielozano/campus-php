<?php
/**
 * @author  Daniel Lozano Morales <dn.lozano.m@gmail.com>
 */
namespace Framework\Routing;
/**
 * La función de esta clase es identificar el controlador que debe ser ejecutado, y que parámetros deben ser pasados a dicho controlador.
 */
class ControllerResolver
{
	/**
	 * Obtener el controlador de la ruta
	 * 
	 * @return string
	 */
	public function getController($request)
	{
		$controller = $request['route']->getController();

		if (is_object($controller)) {
			return $controller;
		}

		// Capturar si el controlador está mal formado.
		if (!strpos($controller, ':')) {
			throw new \InvalidArgumentException(sprintf("Controller %s cant not be found", $controller));
		}

		list($class, $method) = explode(':', $controller, 2);

		// Capturar si el controlador no existe
		if (!class_exists($class)) {
			throw new \InvalidArgumentException(sprintf("Class %s does not exist", $class));
		}

		// Comprobar si es posible llamar al controlador
		$callable = array($this->createControllerObject($class), $method);
		
		if (!is_callable($callable)){
			throw new \InvalidArgumentException("The controller is not callable");	
		}

		return $callable;
	}

	/**
	 * Devolver un objeto de la clase controlador
	 * 
	 * @param  string $class
	 * 
	 * @return $class  Objeto de la clase $class()
	 */
	private function createControllerObject($class)
	{
		return new $class();
	}

	/**
	 * [resolveArguments description]
	 * 
	 * @return array
	 */
	public function getArguments($requestArguments, $controller)
	{
		if (is_array($controller)) {
			// En caso de que tengamos el objeto y su método
			$reflection = new \ReflectionMethod($controller[0], $controller[1]);
		} elseif (is_object($controller) && $controller instanceof \Closure) {
			// En caso de que sea un closure
			$reflection = new \ReflectionFunction($controller);
		}
		$parameters = $reflection->getParameters();
		$arguments = array();
		foreach ($parameters as $param) {
			$paramName = $param->getName();
			if (array_key_exists($paramName, $requestArguments)) {
				$arguments[$paramName] = $requestArguments[$paramName];
			}
		}
		return $arguments;
	}
}