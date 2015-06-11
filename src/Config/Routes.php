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

            $this->add('/{adminModel}', ['action' => 'display'])->setName('backend-display');
            $this->add('/{adminModel}/async', ['action' => 'async'])->setName('backend-display-async');
            $this->add('/{adminModel}/{adminModelId:[\d]+}/delete', ['action' => 'delete'])->setName('backend-delete');
            $this->add('/{adminModel}/{adminModelId:[\d]+}/edit', ['action' => 'edit'])->setName('backend-edit');
            $this->add('/{adminModel}/create', ['action' => 'create'])->setName('backend-create');
        }
    }
}
