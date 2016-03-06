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
	/**
	 * Entorno de la aplicación
	 * 
	 * @var string
	 */
	private $enviroment;

	/**
	 * Registro global a toda la aplicación
	 * 
	 * @var array
	 */
	private static $registry;

	/**
	 * Constructor
	 * 
	 * @param string $enviroment
	 */
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
		$this->registerModules();
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

	/**
	 * Leer listado de módulos y generar autoloading dinámico
	 * mediante composer
	 */
	private function registerModules()
	{
		$modules = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'modules.json');

		$modules = json_decode($modules);

		$loader = self::registry('loader');

		foreach ($modules as $module) {
			$moduleNamespace = str_replace('_', '\\', $module);
			$moduleVendorName = explode('\\', $moduleNamespace)[0];
			$moduleDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $moduleVendorName;
			// comprobar si existe el directorio, si no es inútil añadir dicho prefixo al autoloader
			if (!is_dir($moduleDir)) {
				throw new InvalidArgumentException("Module $moduleNamespace declared, but directory $moduleDir does not exists", 1);
			}
			// Añadir namespace para cada módulo mediante psr4 autoloading
			$loader->addPsr4($moduleVendorName . '\\', $moduleDir);
		}
	}

	/**
	 * Registrar valor en el contenedor global
	 * 
	 * @param  string $key
	 * @param  mixed $value
	 */
	public static function register($key, $value)
	{
		self::$registry[$key] = $value;
	}

	/**
	 * Obtener valor del registro global
	 * 
	 * @param  string $key
	 * @return mixed
	 */
	public static function registry($key)
	{
		return self::$registry[$key];
	}
}