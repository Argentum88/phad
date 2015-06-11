<?php namespace App\Modules\Backend\Interfaces;

interface ColumnFilterInterface
{

	/**
	 * Initialize column filter
	 */
	public function initialize();

	public function apply($repository, $column, $query, $search);

} 