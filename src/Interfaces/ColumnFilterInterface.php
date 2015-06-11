<?php namespace Argentum88\Phad\Interfaces;

interface ColumnFilterInterface
{

	/**
	 * Initialize column filter
	 */
	public function initialize();

	public function apply($repository, $column, $query, $search);

} 