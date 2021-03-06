<?php namespace Argentum88\Phad\Interfaces;

interface FormItemInterface
{

	/**
	 * Initialize form item
	 */
	public function initialize();

	/**
	 * Set currently rendered instance
	 * @param mixed $instance
	 */
	public function setInstance($instance);

	/**
	 * Get form item validation rules
	 * @return mixed
	 */
	public function getValidationRules();

    public function validation($validation);

	/**
	 * Save form item
	 */
	public function save();

} 