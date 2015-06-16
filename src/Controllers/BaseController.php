<?php

namespace Argentum88\Phad\Controllers {

    use Phalcon\Mvc\Controller;

    class BaseController extends Controller
    {

        /**
         * Первое действие
         */
        public function onConstruct()
        {

            $ip = $this->request->getClientAddress(true);

            if ($ip != '127.0.0.1') {

                return $this->notFound();
            }

            $this->view->setLayout('Slidebar');
        }

        /**
         *
         */
        public function notFound()
        {
            return $this->dispatcher->forward(['controller' => 'Error', 'action' => 'error404']);
        }
    }
}
