<?php
/**
 * Phalcon Framework
 * This source file is subject to the New BSD License that is bundled
 * with this package in the file docs/LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@phalconphp.com so we can send you a copy immediately.
 *
 * @author Nikita Vershinin <endeveit@gmail.com>
 */
namespace App\Modules\Backend\Library\Paginator\Pager\Layout {

    use App\Modules\Backend\Library\Paginator\Pager\Layout;

    /**
     * \App\Modules\Backend\Library\Paginator\Pager\Layout\Bootstrap
     * Pager layout that uses Twitter Bootstrap styles.
     */
    class Myhit extends Layout
    {

        /**
         * {@inheritdoc}
         * @var string
         */
        protected $template = '<li><a href="{%url}">{%page}</a></li>';

        /**
         * {@inheritdoc}
         * @var string
         */
        protected $selectedTemplate = '<li><a class="active">{%page}</a></li>';

        /**
         * {@inheritdoc}
         * @param  array $options
         * @return string
         */
        public function getRendered(array $options = array())
        {
            $result = '<div class="pagination"><ul>';

            $bootstrapSelected = '<li class="disabled"><span>{%page}</span></li>';
            $originTemplate = $this->selectedTemplate;
            $this->selectedTemplate = $bootstrapSelected;

            $this->addMaskReplacement('page', '←', true);
            $options['page_number'] = $this->pager->getPreviousPage();
            $result .= $this->processPage($options);

            $this->selectedTemplate = $originTemplate;
            $this->removeMaskReplacement('page');
            $result .= parent::getRendered($options);

            $this->selectedTemplate = $bootstrapSelected;

            $this->addMaskReplacement('page', '→', true);
            $options['page_number'] = $this->pager->getNextPage();
            $result .= $this->processPage($options);

            $this->selectedTemplate = $originTemplate;

            $result .= '</ul>';

            return $result;
        }
    }
}
