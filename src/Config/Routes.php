<?php

namespace Argentum88\Phad\Config {

    use Phalcon\Mvc\Router\Group as RouterGroup;

    /**
     * Class Routes роуты панели управления
     *
     * @package App\Modules\Backend\Config
     */
    class Routes extends RouterGroup
    {
        public function initialize()
        {

            $this->setPrefix('/admin');

            $defaultPaths = [
                'module'     => 'Backend',
                'namespace'  => 'Argentum88\Phad\Controllers',
                'controller' => 'Index',
            ];
            $this->setPaths($defaultPaths);

            // главная
            $arr = [
                'controller' => 'Index',
                'action'     => 'index'
            ];
            $this->add('', $arr)->setName('backend-index');

            /*$this->add('/user', ['controller' => 'User', 'action' => 'index'])->setName('backend-user-main');
            $this->add('/user/create', ['controller' => 'User', 'action' => 'edit'])->setName('backend-user-create');
            $this->add('/user/edit/{id:[\d]+}', ['controller' => 'User', 'action' => 'edit'])->setName('backend-user-edit');
            $this->addPost('/user/delete/{id:[\d]+}', ['controller' => 'User', 'action' => 'delete'])->setName('backend-user-delete');

            $this->add('/seorules', ['controller' => 'Seorules', 'action' => 'index'])->setName('backend-seorules-main');
            $this->add('/seorules/create', ['controller' => 'Seorules', 'action' => 'edit'])->setName('backend-seorules-create');
            $this->add('/seorules/edit/{id:[\d]+}', ['controller' => 'Seorules', 'action' => 'edit'])->setName('backend-seorules-edit');
            $this->add('/seorules/update', ['controller' => 'Seorules', 'action' => 'update'])->setName('backend-seorules-update');

            $this->add('/seotitles', ['controller' => 'Seotitles', 'action' => 'index'])->setName('backend-seotitles-main');
            $this->add('/seotitles/create', ['controller' => 'Seotitles', 'action' => 'edit'])->setName('backend-seotitles-create');
            $this->add('/seotitles/edit/{id:[\d]+}', ['controller' => 'Seotitles', 'action' => 'edit'])->setName('backend-seotitles-edit');
            $this->addPost('/seotitles/delete/{id:[\d]+}', ['controller' => 'Seotitles', 'action' => 'delete'])->setName('backend-seotitles-delete');*/

            $this->add('/{adminModel}', ['action' => 'display'])->setName('backend-display');
            $this->add('/{adminModel}/async', ['action' => 'async'])->setName('backend-display-async');
            $this->add('/{adminModel}/{adminModelId:[\d]+}/delete', ['action' => 'delete'])->setName('backend-delete');
            $this->add('/{adminModel}/{adminModelId:[\d]+}/edit', ['action' => 'edit'])->setName('backend-edit');
            $this->add('/{adminModel}/create', ['action' => 'create'])->setName('backend-create');
        }
    }
}
