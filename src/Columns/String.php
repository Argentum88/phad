<?php namespace App\Modules\Backend\Columns;

class String extends NamedColumn
{

	/**
	 * @return string
	 */
	public function render()
	{
		$params = [
			'value'  => $this->getValue($this->instance, $this->name()),
			'append' => $this->append(),
		];
		return $this->di->get('viewSimple')->render('Columns/string', $params);
	}

}