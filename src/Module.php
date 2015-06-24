<?php

namespace Argentum88\Phad {

    use Argentum88\Phad\Auth\Auth;
    use Phalcon\Loader;
    use Phalcon\Mvc\View as ViewEngine;
    use Phalcon\Mvc\ModuleDefinitionInterface;
    use Phalcon\Assets\Manager as AssetsManager;
    use Phalcon\Assets\Filters\None as AssetsNullFilter;
    use Phalcon\Flash\Session as FlashSession;
    use Phalcon\Mvc\View\Simple;
    use Phalcon\Session\Adapter\Files as Session;

    class Module implements ModuleDefinitionInterface
    {

        /**
         *
         */
        public function registerAutoloaders()
        {

        }

        /**
         *
         * @param \Phalcon\DI $di
         */
        public function registerServices($di)
        {
            Admin::instance();

            require(__DIR__ . '/../../../../app/Config/Phad.php');

            $di['view'] = function () {

                $view = new ViewEngine();
                $view->setViewsDir(__DIR__ . '/Views/');
                $view->setLayoutsDir('Layouts/');
                $view->setPartialsDir('Partials/');

                return $view;
            };

            $di['viewSimple'] = function () {

                $view = new Simple();
                $view->setViewsDir(__DIR__ . '/Views/');

                return $view;
            };

            $di['flashSession'] = function () {

                $flashClasses = [
                    'error'   => 'alert alert-danger',
                    'success' => 'alert alert-success',
                    'notice'  => 'alert alert-info',
                    'warning' => 'alert alert-warning'
                ];

                return new FlashSession($flashClasses);
            };

            $di['session'] = function () {

                $session = new Session();
                $session->start();

                return $session;
            };

            $di['phadAuth'] = function () {

                return new Auth();
            };

            $di['assets'] = function () use ($di) {

                $options = [
                    'sourceBasePath' => __DIR__ . '/Assets/',
                    'targetBasePath' => __DIR__ . '/../../../../public/backend-assets/'
                ];
                $assets  = new AssetsManager($options);

                $assets->collection('backend_css')
                    ->setTargetPath('final.css')
                    ->setTargetUri('backend-assets/final.css')
                    ->addCss('bootstrap/css/bootstrap.min.css')
                    ->addCss('css/styles.css')
                    ->join(true)
                    ->addFilter(new AssetsNullFilter());

                $assets->collection('backend_js')
                    ->setTargetPath('final.js')
                    ->setTargetUri('backend-assets/final.js')
                    ->addJs('bootstrap/js/bootstrap.min.js')
                    ->addJs('js/custom.js')
                    ->join(true)
                    ->addFilter(new AssetsNullFilter());

                return $assets;
            };
        }
    }
}
