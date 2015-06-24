<?php

namespace Argentum88\Phad\Auth;

use Phalcon\Mvc\User\Component;
use Argentum88\Phad\Auth\Forms\LoginForm;
use Argentum88\Phad\Auth\Models\PhadAdministrators;

class Auth extends Component
{
    /**
     * Checks the user credentials
     *
     * @param  array $credentials
     * @throws Exception
     * @return boolean
     */
    public function check($credentials)
    {
        $user = PhadAdministrators::findFirstByName(strtolower($credentials['name']));

        if ($user == false) {

            throw new Exception('Wrong email/password combination');
        }
        if (!$this->security->checkHash($credentials['password'], $user->password)) {

            throw new Exception('Wrong email/password combination');
        }

        $this->setIdentity($user);
    }

    /**
     * Set identity in session
     *
     * @param object $user
     */
    private function setIdentity($user)
    {
        $st_identity = array(
            'id'    => $user->id,
            'name'  => $user->name,
        );
        if ($user->profile) {
            $st_identity['profile_picture'] = $user->profile->getPicture();
        }
        $this->session->set('phad-auth-identity', $st_identity);
    }

    /**
     * Login user - normal way
     *
     * @param  LoginForm $form
     * @return \Phalcon\Http\ResponseInterface
     */
    public function login($form)
    {
        if (!$this->request->isPost()) {


        } else {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flashSession->error($message->getMessage());
                }
            } else {
                $this->check(array(
                        'name'    => $this->request->getPost('name'),
                        'password' => $this->request->getPost('password'),
                    ));

                return $this->response->redirect($this->url->get(['for' => 'backend-index']));
            }
        }
        return false;
    }

    /**
     * Check if the user is signed in
     *
     * @return boolean
     */
    public function isUserSignedIn()
    {
        $identity = $this->getIdentity();
        if (is_array($identity)) {
            if (isset($identity['id'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('phad-auth-identity');
    }

    /**
     * Returns the name of the user
     *
     * @return string
     */
    public function getUserName()
    {
        $identity = $this->session->get('phad-auth-identity');
        return isset($identity['name']) ? $identity['name'] : false;
    }

    /**
     * Returns the id of the user
     *
     * @return string
     */
    public function getUserId()
    {
        $identity = $this->session->get('phad-auth-identity');
        return isset($identity['id']) ? $identity['id'] : false;
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        $this->session->remove('phad-auth-identity');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     * @return bool
     */
    public function authUserById($id)
    {
        $user = PhadAdministrators::findFirstById($id);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }
        $this->setIdentity($user);
        return true;
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @throws Exception
     * @return PhadAdministrators
     */
    public function getUser()
    {
        $identity = $this->session->get('phad-auth-identity');
        if (!isset($identity['id'])) {
            return false;
        }
        $user = PhadAdministrators::findFirstById($identity['id']);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }
        return $user;
    }
} 