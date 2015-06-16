<?php

namespace Argentum88\Phad\Controllers {

    use Argentum88\Phad\Admin;
    use Argentum88\Phad\Interfaces\Renderable;

    /**
     * Class IndexController
     *
     * @property \Phalcon\Mvc\View view
     */
    class IndexController extends BaseController
    {

        public function indexAction()
        {

        }

        public function displayAction()
        {
            $adminModel = $this->dispatcher->getParam('adminModel');
            $class      = array_search($adminModel, Admin::modelAliases());
            $model      = Admin::model($class);
            $content    = $this->render($model->title(), $model->display());

            return $this->response->setContent($content);
        }

        public function asyncAction()
        {
            $adminModel = $this->dispatcher->getParam('adminModel');
            $class      = array_search($adminModel, Admin::modelAliases());
            $model      = Admin::model($class);

            $display = $model->display();
            $data    = $display->renderAsync();

            $this->response->setContentType('application/json');
            return $this->response->setJsonContent($data);
        }

        public function deleteAction()
        {
            $adminModel = $this->dispatcher->getParam('adminModel');
            $class      = array_search($adminModel, Admin::modelAliases());
            $model      = Admin::model($class);
            $id         = $this->dispatcher->getParam('adminModelId');

            $model->repository()->delete($id);

            return $this->response->redirect($this->request->getHTTPReferer());
        }

        public function editAction()
        {
            $adminModel = $this->dispatcher->getParam('adminModel');
            $class      = array_search($adminModel, Admin::modelAliases());
            $model      = Admin::model($class);
            $id         = $this->dispatcher->getParam('adminModelId');

            $edit = $model->fullEdit($id);

            if ($this->request->isPost()) {

                if ($edit->validate($model)) {

                    $edit->save($model);
                    return $this->response->redirect($model->displayUrl());
                }
            }

            $content = $this->render($model->title(), $edit);
            return $this->response->setContent($content);
        }

        public function createAction()
        {
            $adminModel = $this->dispatcher->getParam('adminModel');
            $class      = array_search($adminModel, Admin::modelAliases());
            $model      = Admin::model($class);

            $create = $model->create();

            if ($this->request->isPost()) {

                if ($create->validate($model)) {

                    $create->save($model);
                    return $this->response->redirect($model->displayUrl());
                }
            }

            $content = $this->render($model->title(), $create);
            return $this->response->setContent($content);
        }

        public function render($title, $content)
        {
            if ($content instanceof Renderable)
            {
                $content = $content->render();
            }
            return $this->view->getRender('Index', 'render', [
                    'title'   => $title,
                    'content' => $content,
                ]);
        }
    }
}
