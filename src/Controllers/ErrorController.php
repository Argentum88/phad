<?php

namespace Argentum88\Phad\Controllers {

    use Phalcon\Mvc\View;

    class ErrorController extends BaseController
    {

        /**
         * Error message
         *
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function errorAction($message)
        {

        }

        /**
         * 404 page
         */
        public function error404Action()
        {

            $this->response
                ->resetHeaders()
                ->setStatusCode('404', 'Not Found');

            $this->view->pick('Error/error404');
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        }

        public function error403Action()
        {

            $this->response
                ->resetHeaders()
                ->setStatusCode('403', 'Forbidden');

            $this->view->pick('Error/error403');
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        }

        /**
         * 502 page
         */
        public function error502Action()
        {

            $this->response
                ->resetHeaders()
                ->setStatusCode('502', 'Bad Gateway');

            $this->view->pick('Error/error502');
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        }
    }
}
