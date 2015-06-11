<?php namespace Argentum88\Phad\Interfaces;

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