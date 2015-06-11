<?php namespace Argentum88\Phad\ColumnFilters;

use Argentum88\Phad\Interfaces\ColumnFilterInterface;
use Argentum88\Phad\Interfaces\Renderable;
use Phalcon\DI;

abstract class BaseColumnFilter implements Renderable, ColumnFilterInterface
{
    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;
	protected $view;

    public function __construct()
    {
        $this->di = DI::getDefault();
    }

	/**
	 * Initialize column filter
	 */
	public function initialize()
	{
        $this->di->get('assets')->collection('baseColumnFilterJs')
            ->setTargetPath('baseColumnFilter.js')
            ->setTargetUri('backend-assets/baseColumnFilter.js')
            ->addJs('js/columnfilters/base.js');
	}


	protected function getParams()
	{
		return [];
	}

	public function render()
	{
		$params = $this->getParams();
        return $this->di->get('viewSimple')->render('Columnfilter/' . $this->view, $params);
	}

	/**
	 * @return string
	 */
	function __toString()
	{
		return (string)$this->render();
	}

} 