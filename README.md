# Phad

*Note: this is development version. I do not recommend you to use it in a production environment.*

Phad is administrative interface builder for Phalcon framework, that is based on [sleeping-owl/admin](https://github.com/sleeping-owl/admin).

## Installation

1. Require this package in your composer.json and run composer update:

		"argentum88/phad": "dev-master"

2. Add group of routes to main router

    ```php
    use Argentum88\Phad\Config\Routes as BackendRoutes;

    $router->mount(new BackendRoutes);
    ```
3. Register module

    ```php
	$application->registerModules(['Backend'  => [
        'className' => 'Argentum88\Phad\Module'
    ]]);
    ```
4. 