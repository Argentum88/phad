<?php namespace Argentum88\Phad;;

use Phalcon\DI;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query\Builder;

class BaseRepository
{

    protected $di;

	/**
	 * Repository related class name
	 * @var string
	 */
	protected $class;

	/**
	 * Repository related model instance
	 * @var mixed
	 */
	protected $model;
    protected $belongsTo = [];

	/**
	 * @param string $class
	 */
	function __construct($class)
	{
        $this->di = DI::getDefault();
		$this->class = $class;
		$this->model($this->class);
	}

    public function belongsTo($belongsTo = null)
    {
        if (is_null($belongsTo))
        {
            return $this->belongsTo;
        }

        $this->belongsTo = $belongsTo;
        return $this;
    }

	/**
	 * Get base query
	 * @return Builder
	 */
	public function query()
	{
        $query = new Builder(null);
        $query->setDI($this->di);
        $query->from($this->class);

        $belongsTo = $this->belongsTo();
        if (!empty($belongsTo)) {

            $query->innerJoin($belongsTo['reference_model'], $belongsTo['condition'], $belongsTo['alias']);
        }

		return $query;
	}

	/**
	 * Find model instance by id
	 * @param int $id
	 * @return mixed
	 */
	public function find($id)
	{
        $modelName = $this->class;
		return $modelName::findFirstById($id);
	}

	/**
	 * Find model instances by ids
	 * @param int[] $ids
	 * @return mixed
	 */
	public function findMany($ids)
	{
        $query = new Builder(null);
        $query->setDI($this->di);
        $query->from($this->class);

        return $query->inWhere('id', $ids)->getQuery()->execute();
	}

	/**
	 * Delete model instance by id
	 * @param int $id
	 */
	public function delete($id)
	{
		$this->find($id)->delete();
	}

	/**
	 * Get or set repository related model intance
	 * @param mixed|null $model
	 * @return $this|mixed
	 */
	public function model($model = null)
	{
		if (is_null($model))
		{
			return $this->model;
		}
		$this->model = $model;
		return $this;
	}

	/**
	 * Check if model's table has column
	 * @param string $column
	 * @return bool
	 */
	public function hasColumn($column)
	{
        $model = new $this->class;
        $metadata = $this->di->get('modelsMetadata');
        return $metadata->hasAttribute($model, $column);
	}

}