<?php namespace Argentum88\Phad\ColumnFilters;

class Text extends BaseColumnFilter
{

	protected $view = 'text';
	protected $placeholder;

	/**
	 * Initialize column filter
	 */
	public function initialize()
	{
		parent::initialize();

        $this->di->get('assets')->collection('textColumnFilterJs')
            ->setTargetPath('textColumnFilter.js')
            ->setTargetUri('backend-assets/textColumnFilter.js')
            ->addJs('js/columnfilters/text.js');
	}

	public function placeholder($placeholder = null)
	{
		if (is_null($placeholder))
		{
			return $this->placeholder;
		}
		$this->placeholder = $placeholder;
		return $this;
	}

	protected function getParams()
	{
		return parent::getParams() + [
			'placeholder' => $this->placeholder(),
		];
	}

	public function apply($repository, $column, $query, $search)
	{
		$name = $column->name();
		if ($repository->hasColumn($name)) {

            $query->andWhere("$name LIKE '%$search%'");
        }
	}

}