<?php
/**
 * @author  Daniel Lozano Morales <dn.lozano.m@gmail.com>
 * @license MIT
 */
namespace Framework\Routing;
/**
 * Clase que funcionará como contenedor de rutas.
 */
class RouteCollection
{
	/**
	 * Colección privada de rutas, ya que no queremos
	 * que sea modificada, solo añadir y obtener.
	 * 
	 * @var array
	 */
	private $collection = array();

	/**
	 * Añadir ruta a la colección.
	 * 
	 * @param Framework\Routing\Route $route [description]
	 */
	public function add(Route $route)
	{
		$routeName = $route->getName();
		$this->collection[$routeName] = $route;
	}

	/**
	 * Devuelve una ruta de la colección
	 * 
	 * @return Framework\Routing\Route
	 */
	public function get($name)
	{
		return $this->collection[$name];
	}

	/**
	 * Devuelve la colección de rutas
	 * 
	 * @return array
	 */
	public function getCollection()
	{
		return $this->collection;
	}

}