<?php namespace App\Modules\Backend\Columns;

class Checkbox extends BaseColumn
{

	function __construct()
	{
		parent::__construct();

		$this->label('<input type="checkbox" class="adminCheckboxAll"/>');
		$this->orderable(false);
	}

	/**
	 * Initialize column
	 */
	public function initialize()
	{
		parent::initialize();

        $this->di->get('assets')->collection('dataTablesJs')->addJs('backend-assets/columns/checkbox.js');
	}

	/**
	 * @return string
	 */
	public function render()
	{
		$params = [
			'value' => $this->instance->id,
		];
        return $this->di->get('viewSimple')->render('Columns/checkbox', $params);
	}

}