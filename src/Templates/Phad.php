<?php

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