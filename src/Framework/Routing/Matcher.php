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
	private $collection;

	public function __construct(RouteCollection $collection)
	{
		$this->collection = $collection;
	}

	public function setCollection(RouteCollection $collection)
	{
		$this->collection = $collection;
	}

	public function getCollection()
	{
		return $this->collection;
	}
	/**
	 * Recibirá una petición, cuya request URI debe comparar con la expresión
	 * regular del mapa de rutas, devolviendo así un array con las coincidencias,
	 * tanto de la ruta como de las variables y sus valores.
	 * Con dicha información el dispatcher debe redirigir el flujo de la aplicación
	 * al controlador correspondiente.
	 *
	 * NOTE: si la ruta coincide, obtener sus argumentos por nombre y podemos hacer dispatch.
	 */
	public function match($request)
	{
		$arguments = false;

		foreach ($this->collection->getCollection() as $route) {

			if ($route instanceof Route) {
				// Obtener la expresión regular
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
		}

		return $arguments;
	}
}