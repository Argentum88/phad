<?php namespace Argentum88\Phad\Columns;

class Count extends NamedColumn
{

	/**
	 * @return string
	 */
	public function render()
	{
		$params = [
			'value'  => count($this->getValue($this->instance, $this->name())),
			'append' => $this->append(),
		];
        return $this->di->get('viewSimple')->render('Columns/count', $params);
	}

}