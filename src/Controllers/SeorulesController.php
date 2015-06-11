<?php

namespace App\Modules\Backend\Controllers {

    use Phalcon\Mvc\Dispatcher;
    use ShareModels\SeomonsterRules;

    /**
     * Class SeorulesController
     *
     * @package Modules\Backend\Controllers
     */
    class SeorulesController extends SmartController
    {

        protected $modelName = 'App\SeomonsterRules';
        protected $routeItem = 'seorules';

        /**
         * Обновление правил роутинга в базе
         */
        public function updateAction()
        {
            echo '<pre>';
            $rules = $this->router->getRoutes();

            foreach ($rules as $rule) {

                $name    = $rule->getName();
                $pattern = $rule->getPattern();
                $paths   = $rule->getPaths();

                $placeholders = isset($paths['placeholders']) ? $paths['placeholders'] : '';

                echo sprintf("%s: %s - %s: [%s]\n", $rule->getRouteId(), $name, $pattern, $placeholders);


                $conditions = ['conditions' => 'pattern=?0 AND route_name=?1', 'bind' => [$pattern, $name]];
                if (!$sr = SeomonsterRules::findFirst($conditions)) {

                    $sr = new SeomonsterRules();
                }

                $sr->title        = $name;
                $sr->pattern      = $pattern;
                $sr->route_name   = $name;
                $sr->placeholders = $placeholders;
                $sr->save();
            }

            echo '</pre>';
        }
    }
}
