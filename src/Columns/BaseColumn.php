<?php namespace App\Modules\Backend\Columns;

use App\Modules\Backend\Interfaces\Renderable;
use App\Modules\Backend\Admin;
use App\Modules\Backend\Interfaces\ColumnInterface;
use App\Modules\Backend\ModelConfiguration;
use Phalcon\DI;

abstract class BaseColumn implements Renderable, ColumnInterface
{
    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;

	/**
	 * Column header
	 * @var ColumnHeader
	 */
	protected $header;
	/**
	 * Model instance currently rendering
	 * @var mixed
	 */
	protected $instance;
	/**
	 * Column appendant
	 * @var ColumnInterface
	 */
	protected $append;

	/**
	 *
	 */
	function __construct()
	{
		$this->header = new ColumnHeader;
        $this->di = DI::getDefault();
	}

	/**
	 * Initialize column
	 */
	public function initialize()
	{
	}

	/**
	 * Get related model configuration
	 * @return ModelConfiguration
	 */
	protected function model()
	{
		return Admin::model(get_class($this->instance));
	}

	/**
	 * Set column header label
	 * @param string $title
	 * @return $this
	 */
	public function label($title)
	{
		$this->header->title($title);
		return $this;
	}

	/**
	 * Enable/disable column orderable feature
	 * @param bool $orderable
	 * @return $this
	 */
	public function orderable($orderable)
	{
		$this->header->orderable($orderable);
		return $this;
	}

	/**
	 * Check if column is orderable
	 * @return bool
	 */
	public function isOrderable()
	{
		return $this->header()->orderable();
	}

	/**
	 * Get column header
	 * @return ColumnHeader
	 */
	public function header()
	{
		return $this->header;
	}

	/**
	 * Get or set column appendant
	 * @param ColumnInterface|null $append
	 * @return $this|ColumnInterface
	 */
	public function append($append = null)
	{
		if (is_null($append))
		{
			return $this->append;
		}
		$this->append = $append;
		return $this;
	}

	/**
	 * Set currently rendering instance
	 * @param mixed $instance
	 * @return $this
	 */
	public function setInstance($instance)
	{
		$this->instance = $instance;
		if ( ! is_null($this->append()) && ($this->append() instanceof ColumnInterface))
		{
			$this->append()->setInstance($instance);
		}
		return $this;
	}

	/**
	 * @return string
	 */
	function __toString()
	{
		return (string)$this->render();
	}

}