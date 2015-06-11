<?php namespace Argentum88\Phad;

use Argentum88\Phad\Columns\Control;
use Argentum88\Phad\Columns\String;
use Argentum88\Phad\Columns\NamedColumn;
use Argentum88\Phad\Interfaces\Renderable;
use Argentum88\Phad\Interfaces\DisplayInterface;
use Argentum88\Phad\Interfaces\ColumnInterface;
use Argentum88\Phad\Interfaces\ColumnFilterInterface;
use Phalcon\DI;

class DisplayDatatablesAsync implements Renderable, DisplayInterface
{

    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;
    protected $columns = [];
    protected $columnFilters = [];
    protected $actions = [];
    protected $filters = [];
    protected $activeFilters = [];
    protected $controlActive = true;
    protected $repository;
    protected $class;

    /**
     * Datatables name
     * @var string
     */
    protected $name;

    /**
     * @param string|null $name
     */
    function __construct($name = null)
    {
        $this->name($name);
        $this->di = DI::getDefault();
    }

    public function initialize()
    {
        $this->repository = new BaseRepository($this->class);
        //$this->repository->with($this->with());

        $this->initializeFilters();

        foreach ($this->allColumns() as $column)
        {
            if ($column instanceof ColumnInterface)
            {
                $column->initialize();
            }
        }

        foreach ($this->columnFilters() as $columnFilter)
        {
            if ($columnFilter instanceof ColumnFilterInterface)
            {
                $columnFilter->initialize();
            }
        }

        $this->di->get('assets')->collection('dataTablesJs')
            ->addJs('backend-assets/datatables/jquery.dataTables.min.js')
            ->addJs('backend-assets/datatables/jquery.dataTables_bootstrap.js')
            ->addJs('backend-assets/datatables/init.js')
            ->addJs('backend-assets/notify-combined.min.js');

        $this->di->get('assets')->collection('dataTablesCss')->addCss('backend-assets/dataTables.bootstrap.css');
    }

    protected function initializeFilters()
    {
        $this->initializeAction();
        foreach ($this->filters() as $filter)
        {
            $filter->initialize();
            if ($filter->isActive())
            {
                $this->activeFilters[] = $filter;
            }
        }
    }

    protected function initializeAction()
    {
        $action = $this->di->get('request')->getQuery('_action', 'string', null);
        $id = $this->di->get('request')->getQuery('_id', 'int', null);
        $ids = $this->di->get('request')->getQuery('_ids', null, null);
        if ( ! is_null($action) && ( ! is_null($id) || ! is_null($ids)))
        {
            $columns = array_merge($this->columns(), $this->actions());
            foreach ($columns as $column)
            {
                if ( ! $column instanceof NamedColumn) continue;

                if ($column->name() == $action)
                {
                    $param = null;
                    if ( ! is_null($id))
                    {
                        $param = $this->repository->find($id);
                    } else
                    {
                        $ids = explode(',', $ids);
                        $param = $this->repository->findMany($ids);
                    }
                    $column->call($param);
                }
            }
        }
    }

    public function setClass($class)
    {
        if (is_null($this->class))
        {
            $this->class = $class;
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        $params = $this->getParams();
        $attributes = $this->di->get('request')->getQuery();
        unset($attributes['_url']);
        //array_unshift($attributes, $this->name());
        //array_unshift($attributes, $this->model()->alias());
        $params['url'] = $this->di->get('url')->get(['for' => 'backend-display-async', 'adminModel' => $this->model()->alias()], $attributes);
        return $this->di->get('viewSimple')->render('Display/datatablesAsync', $params);
    }

    protected function getParams()
    {
        $params = [
            //'title'     => $this->title(),
            'columns'   => $this->allColumns(),
            'creatable' => ! is_null($this->model()->create()),
            'createUrl' => $this->model()->createUrl(),
            'actions'   => $this->actions(),
        ];

        //$params['order'] = $this->order();
        $params['columnFilters'] = $this->columnFilters();
        //$params['attributes'] = $this->attributes();

        return $params;
    }

    /**
     * Render async request
     * @return array
     */
    public function renderAsync()
    {
        $query = $this->repository->query();
        $totalCount = count($query->execute());

        $this->applySearch($query);
        $this->applyColumnSearch($query);
        $this->modifyQuery($query);

        $filteredCount = count($query->execute());

        $this->applyOrders($query);
        $this->applyOffset($query);
        $collection = $query->execute();

        return $this->prepareDatatablesStructure($collection, $totalCount, $filteredCount);
    }

    protected function modifyQuery($query)
    {
        foreach ($this->activeFilters as $filter)
        {
            $filter->apply($query);
        }
    }

    /**
     * Apply offset and limit to the query
     * @param $query
     */
    protected function applyOffset($query)
    {
        $offset = $this->di->get('request')->getQuery('start', 'int', 0);
        $limit  = $this->di->get('request')->getQuery('length', 'int', 10);
        if ($limit == -1)
        {
            return;
        }
        $query->limit($limit, $offset);
    }

    /**
     * Apply orders to the query
     * @param $query
     */
    protected function applyOrders($query)
    {
        $orders = $this->di->get('request')->getQuery('order');
        foreach ($orders as $order)
        {
            $columnIndex = $order['column'];
            $orderDirection = $order['dir'];
            $column = $this->allColumns()[$columnIndex];
            if ($column instanceof NamedColumn && $column->isOrderable())
            {
                $name = $column->name();
                $query->orderBy("$name  $orderDirection");
            }
        }
    }

    /**
     * Apply search to the query
     * @param $query
     */
    protected function applySearch($query)
    {
        $search = $this->di->get('request')->getQuery('search')['value'];
        if (empty($search))
        {
            return;
        }

        $columns = $this->columns();
        foreach ($columns as $column)
        {
            if ($column instanceof String)
            {
                $name = $column->name();
                if ($this->repository->hasColumn($name))
                {
                    $query->orWhere("$name LIKE '%$search%'");
                }
            }
        }
    }

    protected function applyColumnSearch($query)
    {
        $queryColumns = $this->di->get('request')->getQuery('columns', null, []);
        foreach ($queryColumns as $index => $queryColumn)
        {
            $search = !empty($queryColumn['search']['value']) ? $queryColumn['search']['value'] : null;
            if (empty($search)) continue;

            $column = $this->columns()[$index];
            $columnFilter = $this->columnFilters()[$index];
            $columnFilter->apply($this->repository, $column, $query, $search);
        }
    }

    /**
     * Convert collection to the datatables structure
     *
     * @param $collection
     * @param $totalCount
     * @param $filteredCount
     * @return array
     */
    protected function prepareDatatablesStructure($collection, $totalCount, $filteredCount)
    {
        $columns = $this->allColumns();

        $result = [];
        $result['draw'] = $this->di->get('request')->getQuery('draw', 'int', 0);
        $result['recordsTotal'] = $totalCount;
        $result['recordsFiltered'] = $filteredCount;
        $result['data'] = [];
        foreach ($collection as $instance)
        {
            $_row = [];
            foreach ($columns as $column)
            {
                $column->setInstance($instance);
                $_row[] = (string)$column;
            }
            $result['data'][] = $_row;
        }
        return $result;
    }

    /**
     * Get or set datatables name
     * @param null $name
     * @return $this
     */
    public function name($name = null)
    {
        if (is_null($name))
        {
            return $this->name;
        }
        $this->name = $name;
        return $this;
    }

    public function columns($columns = null)
    {
        if (is_null($columns))
        {
            return $this->columns;
        }
        $this->columns = $columns;
        return $this;
    }

    public function columnFilters($columnFilters = null)
    {
        if (is_null($columnFilters))
        {
            return $this->columnFilters;
        }
        $this->columnFilters = $columnFilters;
        return $this;
    }

    public function actions($actions = null)
    {
        if (is_null($actions))
        {
            foreach ($this->actions as $action)
            {
                $action->url($this->model()->displayUrl([
                            '_action' => $action->name(),
                            '_ids'    => '',
                        ]));
            }
            return $this->actions;
        }
        $this->actions = $actions;
        return $this;
    }

    public function allColumns()
    {
        $columns = $this->columns();
        if ($this->controlActive())
        {
            $columns[] = new Control();
        }
        return $columns;
    }

    public function controlActive($controlActive = null)
    {
        if (is_null($controlActive))
        {
            return $this->controlActive;
        }
        $this->controlActive = $controlActive;
        return $this;
    }

    public function filters($filters = null)
    {
        if (is_null($filters))
        {
            return $this->filters;
        }
        $this->filters = $filters;
        return $this;
    }

    public function enableControls()
    {
        $this->controlActive(true);
        return $this;
    }

    public function disableControls()
    {
        $this->controlActive(false);
        return $this;
    }

    public function model()
    {
        return Admin::model($this->class);
    }
}