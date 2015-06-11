<?php namespace App\Modules\Backend\Columns;

use App\Modules\Backend\Admin;

class Filter extends NamedColumn
{

	/**
	 * Filter related model
	 * @var string
	 */
	protected $model;
	/**
	 * Field to get filter value from
	 * @var string
	 */
	protected $field;

	/**
	 * Get or set filter related model
	 * @param string|null $model
	 * @return $this|string
	 */
	public function model($model = null)
	{
		if (is_null($model))
		{
			if (is_null($this->model))
			{
				$this->model(get_class($this->instance));
			}
			return $this->model;
		}
		$this->model = $model;
		return $this;
	}

	/**
	 * Get or set field to get filter value from
	 * @param string|null $field
	 * @return $this|string
	 */
	public function field($field = null)
	{
		if (is_null($field))
		{
			if (is_null($this->field))
			{
				$this->field($this->isSelf() ? $this->name() : 'id');
			}
			return $this->field;
		}
		$this->field = $field;
		return $this;
	}

	/**
	 * Get filter url
	 * @return string
	 */
	public function getUrl()
	{
		$value = $this->getValue($this->instance, $this->field());
		return Admin::model($this->model)->displayUrl([$this->name() => $value]);
	}

	/**
	 * Check if filter applies to the current model
	 * @return bool
	 */
	protected function isSelf()
	{
		return get_class($this->instance) == $this->model();
	}

	/**
	 * @return string
	 */
	public function render()
	{
		$params = [
			'isSelf' => $this->isSelf(),
			'url'    => $this->getUrl(),
			'value'  => $this->getValue($this->instance, $this->field()),
		];
        return $this->di->get('viewSimple')->render('Columns/filter', $params);
	}

}