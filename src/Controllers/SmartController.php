<?php

namespace App\Modules\Backend\Controllers {

    use App\Modules\Backend\Helpers\AutoForm;
    use App\Modules\Backend\Library\Paginator\Pager as BootstrapPager;
    use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

    /**
     * Class VkgroupsController
     *
     * @package App\Modules\Backend\Controllers
     */
    class SmartController extends BaseController
    {

        protected $modelName = '';
        protected $routeItem = '';
        protected $keyField = 'id';
        protected $sortField = 'id';

        /**
         * Список объектов
         *
         */
        public function indexAction()
        {

            $currentPage = abs($this->request->getQuery('page', 'int', 1));

            if ($currentPage == 0) {

                $currentPage = 1;
            }

            $model = $this->modelName;
            $query = $this->modelsManager->createBuilder()->from($model)->orderBy(sprintf('%s DESC', $this->sortField));

            $bootstrapPagerOptions = [
                'layoutClass' => 'App\Modules\Backend\Library\Paginator\Pager\Layout\Bootstrap',
                'rangeLength' => 15,
                'urlMask'     => '?page={%page_number}',
            ];
            $pager = new BootstrapPager(
                new Paginator([
                    'builder' => $query,
                    'limit'   => 100,
                    'page'    => $currentPage
                ]),
                $bootstrapPagerOptions
            );

            $this->view->setVar('pager', $pager);
        }

        /**
         * Создание и сохранение
         *
         * @param bool $key значение ID объекта
         *
         * @return \Phalcon\Http\ResponseInterface
         */
        public function editAction($key = false)
        {

            /** @var \Phalcon\Mvc\Model $model */
            $model     = $this->modelName;
            $modelData = $key == false ? (new $model) : $model::findFirst($key);

            $formAction = ['for' => 'backend-' . $this->routeItem . '-edit', 'id' => $key];

            if ($key == false) {

                $formAction = ['for' => 'backend-' . $this->routeItem . '-create'];
            }

            if ($key != false && $modelData == false) {

                $this->flashSession->notice('Запись не найдена');
                return $this->dispatcher->forward(['action' => 'index']);
            }

            $param = ['formAction' => $formAction, 'edit' => $key];
            $createForm = new AutoForm($modelData, $param);

            if ($this->request->isPost()) {

                $created = $modelData->save($this->request->getPost());

                if (!$created) {

                    foreach ($modelData->getMessages() as $message) {

                        $this->flashSession->error($message);
                    }
                } else {

                    $this->flashSession->success("Сохранено!");
                    $redirectParams = ['for' => 'backend-' . $this->routeItem . '-main', 'id' => $modelData->id];
                    return $this->response->redirect($redirectParams);
                }
            }

            $viewParams = [
                'form'      => $createForm,
                'modelData' => $modelData,
                'key'       => $key
            ];

            $this->view->setVars($viewParams);
        }

        public function deleteAction($key)
        {
            /** @var \Phalcon\Mvc\Model $model */
            $model     = $this->modelName;
            $modelData = $model::findFirst($key);

            if ($modelData) {

                if ($modelData->delete()) {

                    if ($this->request->isAjax()) {

                        $this->response->setHeader('Content-Type', 'application/json');
                        return $this->response->setJsonContent(['state' => 'success'], JSON_UNESCAPED_UNICODE);
                    }

                    $this->flashSession->success("Удалено!");
                    $redirectParams = ['for' => 'backend-' . $this->routeItem . '-main', 'id' => $modelData->id];
                    return $this->response->redirect($redirectParams);

                } else {

                    if ($this->request->isAjax()) {

                        $this->response->setHeader('Content-Type', 'application/json');
                        return $this->response->setJsonContent(['state' => 'error'], JSON_UNESCAPED_UNICODE);
                    }

                    foreach ($modelData->getMessages() as $message) {

                        $this->flashSession->error($message);
                        return $this->dispatcher->forward(['action' => 'index']);
                    }
                }

            } else {

                if ($this->request->isAjax()) {

                    $this->response->setHeader('Content-Type', 'application/json');
                    return $this->response->setJsonContent(['state' => 'error'], JSON_UNESCAPED_UNICODE);
                }

                $this->flashSession->notice('Запись не найдена');
                return $this->dispatcher->forward(['action' => 'index']);
            }
        }
    }
}
