<?php namespace Argentum88\Phad\Columns;

use Phalcon\Mvc\Model\Query\Builder;
use Argentum88\Phad\BaseRepository;

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

    /**
     * @param BaseRepository $repository
     * @param Builder $query
     * @param string $search
     */
    public function search($repository, $query, $search)
    {
         $name = $this->name();
         if ($repository->hasColumn($name)) {

             $query->orWhere($query->getFrom() . ".$name LIKE '%$search%'");
         } else {

             $query->orWhere("$name LIKE '%$search%'");
         }
    }

}