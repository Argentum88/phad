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
4. Сreate a folder backend-assets(with write permissions) in your public folder
5. Put your Phad.php in app/Config/

Example Phad.php
```php
use Argentum88\Phad\Admin;
use Argentum88\Phad\Columns\String;
use Argentum88\Phad\ColumnFilters\Text as TextFilter;
use Argentum88\Phad\DisplayDatatablesAsync;
use Argentum88\Phad\FormDefault;
use Argentum88\Phad\FormItems\Text;
use Phalcon\DI;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

Admin::menu('App\User');

Admin::model('App\User')->title('Users')->display(function ()
    {
        $display = new DisplayDatatablesAsync();

        $display->columnFilters([
                (new TextFilter())->placeholder('Name')
            ]);

        $display->columns([
                (new String('name'))->label('Name'),
                (new String('email'))->label('Email'),
            ]);
        return $display;

    })->createAndEdit(function ()
    {
        $form = new FormDefault();
        $form->items([
                (new Text('name', 'Name'))->validationRules([
                        new PresenceOf(['message' => 'The name is required'])
                    ]),
                (new Text('email', 'Email'))->validationRules([
                        new PresenceOf(['message' => 'The email is required']),
                        new Email(['message' => 'The e-mail is not valid'])
                    ]),
            ]);
        return $form;
    });
```