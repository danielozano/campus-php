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

use Framework\Http\Response;

class TestController
{
	public function bye($a = "world")
	{
		return new Response("Goodbye $a");
	}
}
```

Si queremos podemos definir nuestras rutas, individualmente dentro del módulo mediante un archivo routes.php dentro del directorio del módulo, o bien en un archivo genérico situado en app/config/routes.php

Un ejemplo sería:
app/modules/TestModule/routes.php
```php
<?php

use Framework\Routing\Route;

return array(
	new Route(array(
		'name' => 'TestController1',
		'controller' => 'TestModule\Controller\TestController:bye',
		'path' => '/bye/{a}'
	))
);
```
## Novedades
 * Se ha implementado carga dinámica de módulos mediante composer.
 * Carga dinámica de rutas por módulo.

## Backlog
 * Mejorar librerías del framework.
 * Implementar sistema de Templates.
 * Implementar sistema de caché.
 * Implementar ORM.
 * Añadir Vagrantfile al repositorio.
 * Implementar entornos: prod y dev.
 * Comenzar diseño y desarrollo del Campus.