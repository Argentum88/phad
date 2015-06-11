<?php

namespace App\Modules\Backend\Controllers {

    use App\Modules\Backend\Library\Paginator\Pager as BootstrapPager;
    use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

    class UserController extends SmartController
    {
        protected $modelName = 'Core\Mvc\User\UsersAuth\Models\Users';
        protected $routeItem = 'user';

        public function indexAction()
        {

            $currentPage = abs($this->request->getQuery('page', 'int', 1));

            if ($currentPage == 0) {

                $currentPage = 1;
            }

            $model = $this->modelName;
            $query = $this->modelsManager->createBuilder()->from($model)->orderBy(sprintf('%s DESC', $this->sortField));

            if ($this->request->hasQuery('role')) {

                $role = $this->request->getQuery('role', 'alphanum', 'user');
                $query->where("role = '$role'");
            }

            if ($this->request->hasQuery('banned')) {

                $banned = $this->request->getQuery('banned', 'int', 0);
                $query->andWhere("banned = $banned");
            }

            if ($this->request->hasQuery('activated')) {

                $activated = $this->request->getQuery('activated', 'int', 0);
                $query->andWhere("activated = $activated");
            }

            if ($this->request->hasQuery('search')) {

                $search = $this->request->getQuery('search', 'string');
                $query->andWhere("name LIKE '$search' OR email LIKE '$search'");
            }

            $bootstrapPagerOptions = [
                'layoutClass' => 'App\Modules\Backend\Library\Paginator\Pager\Layout\Bootstrap',
                'rangeLength' => 15,
                'urlMask'     => $this->getUrlMask(),
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
            $this->view->setVar('model', $model);
        }

        protected function getUrlMask()
        {
            $query=$this->request->getQuery();
            unset($query['_url'], $query['page']);

            if (count($query) != 0) {

                $newQuery = http_build_query($query);

                return '?'. $newQuery . '&page={%page_number}';
            } else {

                return '?page={%page_number}';
            }
        }

        public function editAction($key = false)
        {
            if ($this->request->isPost() && $this->request->getPost('password')) {

                $_POST['password'] = $this->security->hash($this->request->getPost('password'));
            }

            return parent::editAction($key);
        }
    }
}
