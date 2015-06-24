<?php

namespace Argentum88\Phad\Auth\Controllers;

use Phalcon\Mvc\Controller;
use Argentum88\Phad\Auth\Forms\LoginForm;
use Argentum88\Phad\Auth\Exception as AuthException;

class AdministratorController extends Controller
{

    public function loginAction()
    {
        if(true === $this->auth->isUserSignedIn())
        {
            $this->response->redirect(array('action' => 'profile'));
        }

        $form = new LoginForm();

        try {
            $this->auth->login($form);
        } catch (AuthException $e) {
            $this->flashSession->error($e->getMessage());
        }

        $this->view->form = $form;
    }
} 