<?php
/**
 * @author Daniel Lozano Morales <dn.lozano.m@gmail.com>
 */
namespace Framework\Routing;
/**
 * La funcionalidad de esta clase es a partir de una petición ser capaz de determinar si hay alguna
 * ruta que coincida con dicha petición. En caso de que así sea poder obtener información de dicha
 * ruta.
 * Necesita una colección de rutas y la petición actual.
 */
class Matcher
{
	/**
	 * Objeto que contiene la colección de rutas
	 * 
	 * @var Framework\Routing\RouteCollection
	 */
	private $collection;

	/**
	 * Constructor
	 * 
	 * @param RouteCollection $collection
	 */
	public function __construct(RouteCollection $collection)
	{
		$this->collection = $collection;
	}

	/**
	 * Establecer la colección
	 * 
	 * @param RouteCollection $collection
	 */
	public function setCollection(RouteCollection $collection)
	{
		$this->collection = $collection;
	}

	/**
	 * Obtener la colección
	 * 
	 * @return Framework\Routing\RouteCollection
	 */
	public function getCollection()
	{
		return $this->collection;
	}

	/**
	 * Recibe la URI de la petición actual, y comprueba si hay definida una ruta
	 * coincidente. En caso de que sí se obtendrán las opciones de dicha ruta.
	 * Y en caso de no existir ruta coincidente simplemente se devolverá False.
	 * 
	 * @param   $request URI De la petición actual
	 * @return  array|false False en caso de que no exista ruta coincidente
	 */
	public function match($request)
	{
		$arguments = false;

		foreach ($this->collection->getCollection() as $route) {

			if ($route instanceof Route) {
				// Obtener la expresión regular de la ruta
				$regex = $route->generateRegex();

				// comprobar si la regex coincide con la petición actual
				$match = preg_match($regex, $request, $matches);

				// En caso de coincidencia proceder a obtener sus argumentos a través de otra regex, y con eso
				// ya podemos hacer dispatch.
				if (1 === $match) {
					$arguments = $route->getRouteArguments($route->getOption('path'), $request);

					// incluir el objeto ruta en los parámetros. NOTE: quizás muy pesado el incluir el propio objeto.
					$arguments['route'] = $route;
				}
			}
		}// end foreach

		return $arguments;
	}
}