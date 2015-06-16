<?php namespace Argentum88\Phad;

use Argentum88\Phad\Interfaces\Renderable;
use Argentum88\Phad\Interfaces\DisplayInterface;
use Argentum88\Phad\Interfaces\FormInterface;
use Argentum88\Phad\Interfaces\FormItemInterface;
use Phalcon\DI;
use Phalcon\Validation;

class FormDefault implements Renderable, DisplayInterface, FormInterface
{

    protected $validation;

    protected $di;

    /**
     * Form related class
     * @var string
     */
    protected $class;
    /**
     * Form related repository
     * @var BaseRepository
     */
    protected $repository;
    /**
     * Form items
     * @var FormItemInterface[]
     */
    protected $items = [];
    /**
     * Form action url
     * @var string
     */
    protected $action;
    /**
     * Form related model instance
     * @var mixed
     */
    protected $instance;
    /**
     * Currently loaded model id
     * @var int
     */
    protected $id;
    /**
     * Is form already initialized?
     * @var bool
     */
    protected $initialized = false;

    public function __construct()
    {
        $this->di = DI::getDefault();
        $this->validation = new Validation();
    }

    /**
     * Initialize form
     */
    public function initialize()
    {
        if ($this->initialized) return;

        $this->initialized = true;
        $this->repository = new BaseRepository($this->class);
        $this->instance(new $this->class);
        foreach ($this->items() as $item)
        {
            $item->validation($this->validation);
            $item->initialize();
        }
    }

    /**
     * Set form action
     * @param string $action
     */
    public function setAction($action)
    {
        if (is_null($this->action))
        {
            $this->action = $action;
        }
    }

    /**
     * Set form class
     * @param string $class
     */
    public function setClass($class)
    {
        if (is_null($this->class))
        {
            $this->class = $class;
        }
    }

    /**
     * Get or set form items
     * @param FormInterface[]|null $items
     * @return $this|FormItemInterface[]
     */
    public function items($items = null)
    {
        if (is_null($items))
        {
            return $this->items;
        }
        $this->items = $items;
        return $this;
    }

    /**
     * Get or set form related model instance
     * @param mixed|null $instance
     * @return \Phalcon\Mvc\Model
     */
    public function instance($instance = null)
    {
        if (is_null($instance))
        {
            return $this->instance;
        }
        $this->instance = $instance;
        foreach ($this->items() as $item)
        {
            $item->setInstance($instance);
        }
        return $this;
    }

    /**
     * Set currently loaded model id
     * @param int $id
     */
    public function setId($id)
    {
        if (is_null($this->id))
        {
            $this->id = $id;
            $this->instance($this->repository->find($id));
        }
    }

    /**
     * Get related form model configuration
     * @return ModelConfiguration
     */
    public function model()
    {
        return Admin::model($this->class);
    }

    /**
     * Save instance
     * @param $model
     */
    public function save($model)
    {
        if ($this->model() != $model)
        {
            return null;
        }

        foreach ($this->items() as $item)
        {
            $item->save();
        }
        $this->instance()->save();
    }

    /**
     * Validate data, returns null on success
     * @param mixed $model
     * @return boolean
     */
    public function validate($model)
    {
        if ($this->model() != $model)
        {
            return null;
        }

        foreach ($this->items() as $item)
        {
            $rules = $item->getValidationRules();
            foreach ($rules as $rule) {

                $this->validation->add($item->name(), $rule);
            }
        }
        $data = $this->di->get('request')->getPost();
        $failMessages = $this->validation->validate($data);
        if (count($failMessages))
        {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function render()
    {
        $params = [
            'items'      => $this->items(),
            'instance'   => $this->instance(),
            'action'     => $this->action,
            'backUrl'    => $this->di->get('url')->get(
                ['for' => 'backend-display', 'adminModel' => $this->model()->alias()]
            )
        ];
        return $this->di->get('viewSimple')->render('Display/formDefault', $params);
    }

    /**
     * @return string
     */
    function __toString()
    {
        return (string)$this->render();
    }

}