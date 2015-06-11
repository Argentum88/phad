<?php namespace App\Modules\Backend\Filters;

use App\Modules\Backend\Interfaces\FilterInterface;
use Phalcon\DI;

abstract class FilterBase implements FilterInterface
{

    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;
	protected $name;
	protected $alias;
	protected $title;
	protected $value;

	function __construct($name)
	{
		$this->name($name);
		$this->alias($name);
        $this->di = DI::getDefault();
	}

	public function name($name = null)
	{
		if (is_null($name))
		{
			return $this->name;
		}
		$this->name = $name;
		return $this;
	}

	public function alias($alias = null)
	{
		if (is_null($alias))
		{
			return $this->alias;
		}
		$this->alias = $alias;
		return $this;
	}

	public function title($title = null)
	{
		if (is_null($title))
		{
			if (is_callable($this->title))
			{
				return call_user_func($this->title, $this->value());
			}
			return $this->title;
		}
		$this->title = $title;
		return $this;
	}

	public function value($value = null)
	{
		if (is_null($value))
		{
			return $this->value;
		}
		$this->value = $value;
		return $this;
	}

	public function initialize()
	{
		$parameters = $this->di->get('request')->getQuery();
		$value = $this->value();
		if (is_null($value))
		{
            $alias = $this->alias();
			$value = isset($parameters[$alias]) ? $parameters[$alias] : null;
		}
		$this->value($value);
	}

	public function isActive()
	{
		return ! is_null($this->value());
	}

	public function apply($query)
	{
        $name = $this->name();
        $value = $this->value();
		$query->andWhere("$name = $value");
	}

}