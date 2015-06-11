<?php namespace Argentum88\Phad\Columns;

use Argentum88\Phad\Interfaces\Renderable;

class ColumnHeader implements Renderable
{

    protected $di;

	/**
	 * Header title
	 * @var string
	 */
	protected $title;
	/**
	 * Is column orderable?
	 * @var bool
	 */
	protected $orderable = true;

    public function __construct()
    {
        $this->di = \Phalcon\DI::getDefault();
    }

	/**
	 * Get or set title
	 * @param string|null $title
	 * @return $this|string
	 */
	public function title($title = null)
	{
		if (is_null($title))
		{
			return $this->title;
		}
		$this->title = $title;
		return $this;
	}

	/**
	 * Get or set column orderable feature
	 * @param bool|null $orderable
	 * @return $this|bool
	 */
	public function orderable($orderable = null)
	{
		if (is_null($orderable))
		{
			return $this->orderable;
		}
		$this->orderable = $orderable;
		return $this;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		$params = [
			'title'     => $this->title(),
			'orderable' => $this->orderable(),
		];
		return $this->di->get('viewSimple')->render('Columns/header', $params);
	}

	/**
	 * @return string
	 */
	function __toString()
	{
		return (string)$this->render();
	}

}