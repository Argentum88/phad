<?php namespace App\Modules\Backend\ColumnFilters;

use App\Modules\Backend\Interfaces\ColumnFilterInterface;
use App\Modules\Backend\Interfaces\Renderable;
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
        $this->di->get('assets')->collection('dataTablesJs')->addJs('backend-assets/columnfilters/base.js');
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