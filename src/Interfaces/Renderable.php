<?php namespace App\Modules\Backend\Interfaces;

interface Renderable {

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return string
	 */
	public function render();

}
