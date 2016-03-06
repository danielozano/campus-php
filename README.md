# Campus PHP
Sistema de gestión estudiantil, que simulará un campus Universitario.

## Estado Actual
Actualmente se está desarrolando el framework sobre el cual se construirá la aplicación.

Para crear un nuevo módulo dentro del framework, debemos defini dicho módulo dentro del listado que encontraremos en /modules/modules.json

Ejemplo:
```json
[
	"TestModule"
]
```
Seguidamente dentro de modules/ debemos generar el directorio de nuestro módulo. Debe seguirse el estándar PSR-4, por lo que la estructura de directorios de cada fichero formará su namespace.

modules/TestModule/Controller/TestController.php
```php
<?php

namespace TestModule\Controller;

class TestController
{
	public function __construct()
	{
		die('Working!');
	}
}
```
## Novedades
 * Se ha implementado carga dinámica de módulos mediante composer.

## Backlog
 * Mejorar librerías del framework.
 * Implementar sistema de Templates.
 * Implementar sistema de caché.
 * Implementar ORM.
 * Añadir Vagrantfile al repositorio.
 * Implementar entornos: prod y dev.
 * Comenzar diseño y desarrollo del Campus.