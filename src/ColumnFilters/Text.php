<?php namespace Argentum88\Phad\ColumnFilters;

use Phalcon\Assets\Filters\None as AssetsNullFilter;
use Phalcon\Mvc\Model\Query\Builder;
use Argentum88\Phad\BaseRepository;

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
            ->addJs('js/columnfilters/text.js')
            ->join(true)
            ->addFilter(new AssetsNullFilter());
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

    /**
     * @param BaseRepository $repository
     * @param mixed $column
     * @param Builder $query
     * @param string $search
     */
	public function apply($repository, $column, $query, $search)
	{
		$name = $column->name();
		if ($repository->hasColumn($name)) {

            $query->andWhere($query->getFrom() . ".$name LIKE '%$search%'");
        } else {

            $query->andWhere("$name LIKE '%$search%'");
        }
	}

}