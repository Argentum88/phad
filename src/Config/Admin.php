<?php

use Argentum88\Phad\Admin;
use Argentum88\Phad\Columns\String;
use Argentum88\Phad\Columns\Action;
use Argentum88\Phad\Columns\Checkbox;
use Argentum88\Phad\Columns\Count;
use Argentum88\Phad\Columns\Filter;
use Argentum88\Phad\ColumnFilters\Text as TextFilter;
use Argentum88\Phad\DisplayDatatablesAsync;
use Argentum88\Phad\FormDefault;
use Argentum88\Phad\FormItems\Text;
use Argentum88\Phad\Filters\FilterField;
use Phalcon\DI;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;



Admin::model('App\User')->title('Users')->display(function ()
    {
        $display = new DisplayDatatablesAsync();

        $display->columnFilters([
                null,
                (new TextFilter())->placeholder('Name')
            ]);

        $display->actions([
                (new Action('export'))->value('Export')->icon('glyphicon glyphicon-share-alt')->callback(function ($collection)
                    {
                        $di = DI::getDefault();
                        foreach ($collection  as $item) {

                            $name = $item->name;
                            $di->get('flashSession')->notice("Применили действие к name=$name");
                        }
                        $di->get('response')->redirect(Admin::model(get_class($collection[0]))->displayUrl())->send();
                        exit();
                    }),
            ]);

        $display->columns([
                (new Checkbox()),
                (new String('name'))->label('Name'),
                (new String('email'))->label('Email'),
                (new Count('cars'))->label('Cars')->orderable(false)->append((new Filter('user_id'))->model('App\Car')),
                (new Action('my_action'))->label('<small>(action)</small>')->value('Custom action')->callback(function ($instance)
                    {
                        $di = DI::getDefault();
                        $id = $instance->id;
                        $di->get('flashSession')->notice("Применили действие к id=$id");
                        $di->get('response')->redirect(Admin::model(get_class($instance))->displayUrl())->send();
                        exit();
                    })
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




Admin::model('App\Car')->title('Car')->display(function ()
    {
        $display = new DisplayDatatablesAsync();

        $display->filters([
                new FilterField('user_id'),
            ]);

        $display->columnFilters([
                null,
                (new TextFilter())->placeholder('Работать не будет')
            ]);

        $display->columns([
                (new String('id'))->label('Id'),
                (new String('user.name'))->label('User name'),
                (new String('vendor'))->label('Vendor'),
                (new String('model'))->label('Model'),
            ]);
        return $display;

    })->createAndEdit(function ()
    {
        $form = new FormDefault();
        $form->items([
                (new Text('user_id', 'User id'))->validationRules([
                        new PresenceOf(['message' => 'The name is required'])
                    ]),
                (new Text('vendor', 'Vendor'))->validationRules([
                        new PresenceOf(['message' => 'The email is required'])
                    ]),
                (new Text('model', 'Model'))->validationRules([
                        new PresenceOf(['message' => 'The email is required'])
                    ]),
            ]);
        return $form;
    });