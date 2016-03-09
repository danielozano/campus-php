<?php

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Router;
use Framework\Routing\Matcher;
/**
 * TODO: añadir archivos de configuración
 * TODO: añadir sistema de template en php
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
	 * Colección de módulos cargados
	 * 
	 * @var array
	 */
	private $moduleCollection = array();
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
		$modules = $this->registerModules();
		// TODO: Necesito añadir las rutas de los módulos registrados a la colección.
		$routeCollection = include_once 'config/routes.php';
		// Obtener las rutas de los módulos cargados
		$moduleRoutes = $this->getModuleRoutes($this->moduleCollection);
		// Añadir las rutas a la colección
		$routeCollection->addAsArray($moduleRoutes);

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

			// añadir el módulo a la colección de módulos
			if (array_key_exists($module, $this->moduleCollection)) {
				throw new \InvalidArgumentException("Error, duplicated module $module");
			}
			$this->moduleCollection[$module] = $moduleDir;
		}
	}

	/**
	 * Devuelve un array de rutas. Contiene las rutas
	 * de todos los módulos.
	 * 
	 * @param  array  $modules [description]
	 * @return array
	 */
	private function getModuleRoutes(array $modules)
	{
		$result = array();
		foreach ($modules as $name => $directory) {
			$routesFile = $directory . DIRECTORY_SEPARATOR . 'routes.php';
			if (file_exists($routesFile)) {
				$routes = include_once $routesFile;
				// TODO: resolver conflicto para rutas con el mismo nombre.
				$result = array_merge($result, $routes);
			}
		}
		return $result;
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