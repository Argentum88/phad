<?php namespace Argentum88\Phad;

use Argentum88\Phad\Helpers\Str;
use Argentum88\Phad\Interfaces\DisplayInterface;
use Argentum88\Phad\Interfaces\FormInterface;
use Phalcon\DI;

class ModelConfiguration
{

    protected $di;
    protected $class;
    protected $alias;
    protected $title;
    protected $display;
    protected $create;
    protected $edit;
    protected $delete = true;
    protected $restore = true;

    function __construct($class)
    {
        $this->di    = DI::getDefault();
        $this->class = $class;
        $this->setDefaultAlias();
    }

    public function repository()
    {
        return new BaseRepository($this->class);
    }

    protected function setDefaultAlias()
    {
        $alias = Str::snake(self::class_basename($this->class));
        $this->alias($alias);
    }

    /**
     * Get the class "basename" of the given object / class.
     *
     * @param  string|object  $class
     * @return string
     */
    protected static function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }

    public function alias($alias = null)
    {
        if (func_num_args() == 0)
        {
            return $this->alias;
        }
        $this->alias = $alias;
        return $this;
    }

    public function title($title = null)
    {
        if (func_num_args() == 0)
        {
            return $this->title;
        }
        $this->title = $title;
        return $this;
    }

    public function create($create = null)
    {
        if (func_num_args() == 0)
        {
            return $this->getCreate();
        }
        $this->create = $create;
        return $this;
    }

    public function edit($edit = null)
    {
        if ((func_num_args() == 0) || is_numeric($edit))
        {
            return $this->getEdit($edit);
        }
        $this->edit = $edit;
        return $this;
    }

    public function createAndEdit($callback)
    {
        $this->create($callback);
        $this->edit($callback);
        return $this;
    }

    public function delete($delete = null)
    {
        if ((func_num_args() == 0) || is_numeric($delete))
        {
            return $this->getDelete($delete);
        }
        $this->delete = $delete;
        return $this;
    }

    public function restore($restore = null)
    {
        if ((func_num_args() == 0) || is_numeric($restore))
        {
            return $this->getRestore($restore);
        }
        $this->restore = $restore;
        return $this;
    }

    public function display($display = null)
    {
        if (func_num_args() == 0)
        {
            return $this->getDisplay();
        }
        $this->display = $display;
        return $this;
    }

    protected function getDisplay()
    {
        $display = call_user_func($this->display);
        if ($display instanceof DisplayInterface)
        {
            $display->setClass($this->class);
            $display->initialize();
        }
        return $display;
    }

    protected function getCreate()
    {
        if (is_null($this->create))
        {
            return null;
        }
        $create = call_user_func($this->create, null);
        if ($create instanceof DisplayInterface)
        {
            $create->setClass($this->class);
            $create->initialize();
        }
        if ($create instanceof FormInterface)
        {
            $create->setAction($this->storeUrl());
        }
        return $create;
    }

    protected function getEdit($id)
    {
        if (is_null($this->edit))
        {
            return null;
        }
        $edit = call_user_func($this->edit, $id);
        if ($edit instanceof DisplayInterface)
        {
            $edit->setClass($this->class);
            $edit->initialize();
        }
        return $edit;
    }

    public function fullEdit($id)
    {
        $edit = $this->edit($id);
        if ($edit instanceof FormInterface)
        {
            $edit->setAction($this->updateUrl($id));
            $edit->setId($id);
        }
        return $edit;
    }

    protected function getDelete($id)
    {
        if (is_callable($this->delete))
        {
            return call_user_func($this->delete, $id);
        }
        return $this->delete;
    }

    protected function getRestore($id)
    {
        if (is_callable($this->restore))
        {
            return call_user_func($this->restore, $id);
        }
        return $this->restore;
    }

    public function displayUrl($parameters = [])
    {
        return $this->di->get('url')->get(['for' => 'backend-display', 'adminModel' => $this->alias()], $parameters);
    }

    public function createUrl()
    {
        return $this->di->get('url')->get(['for' => 'backend-create', 'adminModel' => $this->alias()]);
    }

    public function storeUrl()
    {
        return $this->di->get('url')->get(['for' => 'backend-create', 'adminModel' => $this->alias()]);
    }

    public function editUrl($id)
    {
        return $this->di->get('url')->get(['for' => 'backend-edit', 'adminModel' => $this->alias(), 'adminModelId' => $id]);
    }

    public function updateUrl($id)
    {
        return $this->di->get('url')->get(['for' => 'backend-edit', 'adminModel' => $this->alias(), 'adminModelId' => $id]);
    }

    public function deleteUrl($id)
    {
        return $this->di->get('url')->get(['for' => 'backend-delete', 'adminModel' => $this->alias(), 'adminModelId' => $id]);
    }

}