<?php namespace App\Modules\Backend\Interfaces;

interface ColumnInterface
{

	/**
	 * Initialize column
	 */
	public function initialize();

	/**
	 * Set currently rendered instance
	 * @param mixed $instance
	 */
	public function setInstance($instance);

} 