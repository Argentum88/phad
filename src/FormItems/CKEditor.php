<?php namespace Argentum88\Phad\FormItems;

class CKEditor extends NamedFormItem
{

	protected $view = 'ckeditor';

	public function initialize()
	{
		parent::initialize();

        $this->di->get('assets')->collection('ckEditorFormItemJs')
            ->addJs('backend-assets/ckeditor/ckeditor.js');
	}
}