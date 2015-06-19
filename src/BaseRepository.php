<?php namespace Argentum88\Phad;;

use Phalcon\DI;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\RelationInterface;

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

	/**
	 * @param string $class
	 */
	function __construct($class)
	{
        $this->di = DI::getDefault();
		$this->class = $class;
		$this->model(new $this->class);
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

        $manager = $this->model()->getModelsManager();

        /** @var RelationInterface[] $belongsToRelations */
        $belongsToRelations = $manager->getBelongsTo($this->model());
        if (!empty($belongsToRelations)) {

            foreach ($belongsToRelations as $belongsToRelation) {

                $referencedModel = $belongsToRelation->getReferencedModel();
                $referencedField = $belongsToRelation->getReferencedFields();
                $field           = $belongsToRelation->getFields();
                $alias           = $belongsToRelation->getOptions()['alias'];
                $query->innerJoin($referencedModel, "$alias.$referencedField = $this->class.$field", $alias);
            }
        }

        /** @var RelationInterface[] $hasManyRelations */
        $hasManyRelations = $manager->getHasMany($this->model());
        if (!empty($hasManyRelations)) {

            foreach ($hasManyRelations as $hasManyRelation) {

                $referencedModel = $hasManyRelation->getReferencedModel();
                $referencedField = $hasManyRelation->getReferencedFields();
                $field           = $hasManyRelation->getFields();
                $alias           = $hasManyRelation->getOptions()['alias'];
                $query->leftJoin($referencedModel, "$alias.$referencedField = $this->class.$field", $alias);
                $query->groupBy("$this->class.id");
            }
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
	 * @return $this|Model
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
        $model = $this->model();
        $metadata = $this->di->get('modelsMetadata');
        return $metadata->hasAttribute($model, $column);
	}

}