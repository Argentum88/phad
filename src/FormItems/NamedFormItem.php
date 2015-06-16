<?php namespace Argentum88\Phad\FormItems;


abstract class NamedFormItem extends BaseFormItem
{

    protected $name;
    protected $label;
    protected $defaultValue;

    function __construct($name, $label = null)
    {
        parent::__construct();

        $this->label = $label;
        $this->name = $name;
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

    public function label($label = null)
    {
        if (is_null($label))
        {
            return $this->label;
        }
        $this->label = $label;
        return $this;
    }

    public function getParams()
    {
        return parent::getParams() + [
            'name'  => $this->name(),
            'label' => $this->label(),
            'value' => $this->value(),
        ];
    }

    public function defaultValue($defaultValue = null)
    {
        if (is_null($defaultValue))
        {
            return $this->defaultValue;
        }
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function value()
    {
        $instance = $this->instance();
        /*if ( ! is_null($value = old($this->name())))
        {
            return $value;
        }*/
        if ($this->di->get('request')->hasPost($this->name))
        {
            return $this->di->get('request')->getPost($this->name());
        }
        if ( ! is_null($instance) && ! is_null($value = $instance->readAttribute($this->name())))
        {
            return $value;
        }
        return $this->defaultValue();
    }

    public function save()
    {
        $name = $this->name();
        /*if ( ! Input::has($name))
        {
            Input::merge([$name => null]);
        }*/
        $this->instance()->$name = $this->value();
    }

}