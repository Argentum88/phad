<?php

namespace Argentum88\Phad\Auth\Forms;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Password,
    Phalcon\Forms\Element\Submit,
    Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends Form
{
    public function initialize()
    {
        $name = new Text('name', array(
                'placeholder' => 'Name',
                'class'       => 'form-control'
            ));
        $name->addValidators(array(
                new PresenceOf(array(
                        'message' => 'The name is required'
                    ))
            ));
        $this->add($name);

        $password = new Password('password', array(
                'placeholder' => 'Password',
                'class'       => 'form-control'
            ));
        $password->addValidator(
            new PresenceOf(array(
                    'message' => 'The password is required'
                ))
        );
        $this->add($password);

        $this->add(new Submit('go', array(
                    'class' => 'btn btn-success'
                )));
    }
}