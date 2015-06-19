<?php namespace Argentum88\Phad\Columns;

use Phalcon\Mvc\Model\Query\Builder;
use Argentum88\Phad\BaseRepository;

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

    /**
     * @param BaseRepository $repository
     * @param Builder $query
     * @param string $orderDirection
     */
    public function order($repository, $query, $orderDirection)
    {
        $name = $this->name();
        $query->orderBy("count($name.id) $orderDirection");
    }

}