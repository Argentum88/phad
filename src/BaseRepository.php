<?php namespace Argentum88\Phad;;

use Phalcon\DI;

class BaseRepository
{

    /**
     * @var \Phalcon\DiInterface
     */
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
	/**
	 * Eager loading relations
	 * @var string[]
	 */
	protected $with = [];

	/**
	 * @param string $class
	 */
	function __construct($class)
	{
        $this->di = DI::getDefault();
		$this->class = $class;
		$this->model($this->class);
	}

	/**
	 * Get or set eager loading relations
	 * @param string|string[]|null $with
	 * @return $this|string[]
	 */
	public function with($with = null)
	{
		if (is_null($with))
		{
			return $this->with;
		}
		if ( ! is_array($with))
		{
			$with = func_get_args();
		}
		$this->with = $with;
		return $this;
	}

	/**
	 * Get base query
	 * @return mixed
	 */
	public function query()
	{
        $modelName = $this->model;
		$query = $modelName::query();
		//$query->with($this->with());
		return $query;
	}

	/**
	 * Find model instance by id
	 * @param int $id
	 * @return mixed
	 */
	public function find($id)
	{
        $modelName = $this->model;
		return $modelName::findFirstById($id);
	}

	/**
	 * Find model instances by ids
	 * @param int[] $ids
	 * @return mixed
	 */
	public function findMany($ids)
	{
        $modelName = $this->model;
        $query = $modelName::query();

        return $query->inWhere('id', $ids)->execute();
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
	 * Restore model instance by id
	 * @param int $id
	 */
	public function restore($id)
	{
		$this->query()->onlyTrashed()->find($id)->restore();
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
        $model = new $this->model;
        $metadata = $this->di->get('modelsMetadata');
        return $metadata->hasAttribute($model, $column);
	}

}