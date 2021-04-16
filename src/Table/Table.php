<?php

/**
 * This file is part of the GordyAnsell GreenFedora PHP framework.
 *
 * (c) Gordon Ansell <contact@gordonansell.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);
namespace GreenFedora\Table;

use GreenFedora\Table\TableInterface;
use GreenFedora\Table\ColumnInterface;
use GreenFedora\Table\Column;
use GreenFedora\Html\Html;

use GreenFedora\Table\Exception\InvalidArgumentException;

/**
 * Table maker.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Table implements TableInterface
{
    /**
     * Parameters.
     * @var array
     */
    protected $params = [];

    /**
     * Class.
     * @var string
     */
    protected $class = null;

    /**
     * Columns.
     * @var array
     */
    protected $columns = [];

    /**
     * Data.
     * @var iterable
     */
    protected $data = [];

    /**
     * Table tag.
     * @var string
     */
    protected $tableTag = 'table';

    /**
     * Header tag.
     * @var string
     */
    protected $headTag = 'thead';

    /**
     * Body tag.
     * @var string
     */
    protected $bodyTag = 'tbody';

    /**
     * Row tag.
     * @var string
     */
    protected $rowTag = 'tr';

    /**
     * Do we have sortable columns?
     * @var bool
     */
    protected $hasSortableColumns = false;

    /**
     * Constructor.
     * 
     * @param   string          $name        Table name.
     * @param   string|null     $class       Class.
     * @param   array           $params      Parameters.
     * @return  void
     */
    public function __construct(string $name, ?string $class = null, array $params = [])
    {
        $this->name = $name;
        $this->class = $class;
        $this->params = $params;
    }

    /**
     * Get the name.
     * 
     * @return  string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Add a column.
     * 
     * @param   string|ColumnInterface      $name           Column name or instance.
     * @param   string                      $title          Column title.
     * @param   string|null                 $hdrClass       Column header class.
     * @param   string|null                 $bodyClass      Column body class.
     * @param   array                       $hdrParams      Header parameters.
     * @param   array                       $bodyParams     Body parameters.
     * @return  TableInterface
     */
    public function addColumn($name, string $title = '', ?string $hdrClass = null, 
        ?string $bodyClass = null, array $hdrParams = [], array $bodyParams = []): TableInterface
    {
        if ($name instanceof ColumnInterface) {
            $this->columns[$name->getName()] = $name;
            return $this;
        }

        $this->columns[$name] = new Column($this, $name, $title, $hdrClass, $bodyClass, $hdrParams, $bodyParams);
        return $this;
    }

    /**
     * Add a sortable column.
     * 
     * @param   string|SortableColumnInterface  $name           Column name or instance.
     * @param   string                          $title          Column title.
     * @param   string|null                     $hdrClass       Column header class.
     * @param   string|null                     $bodyClass      Column body class.
     * @param   array                           $hdrParams      Header parameters.
     * @param   array                           $bodyParams     Body parameters.
     * @return  TableInterface
     */
    public function addSortableColumn($name, string $title = '', ?string $hdrClass = null, 
        ?string $bodyClass = null, array $hdrParams = [], array $bodyParams = []): TableInterface
    {
        if ($name instanceof SortableColumnInterface) {
            $this->columns[$name->getName()] = $name;
            return $this;
        }

        $this->columns[$name] = new SortableColumn($this, $name, $title, $hdrClass, $bodyClass, $hdrParams, $bodyParams);
        $this->hasSortableColumns = true;
        return $this;
    }

    /**
     * Get a column.
     * 
     * @param   string     $name      Column name.
     * @return  ColumnInterface
     * @throws  InvalidArgumentException
     */
    public function getColumn(string $name): ColumnInterface
    {
        if (array_key_exists($name, $this->columns)) {
            return $this->columns[$name];
        }
        throw new InvalidArgumentException(sprintf("No column with name '%s' found.", $name));
    }

    /**
     * Get all the columns.
     * 
     * @return  ColumnInterface[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Add a class.
     * 
     * @param   string  $class  Class to add.
     * @return  TableInterface
     */
    public function addClass(string $class): TableInterface
    {
        if (null !== $this->class and '' != $this->class) {
            $this->class .= ' ';
        }
        $this->class .= $class;
        return $this;
    }

    /**
     * Set the class.
     * 
     * @param   string  $class  Class to set.
     * @return  TableInterface
     */
    public function setClass(string $class): TableInterface
    {
        $this->class = $class;
        return $this;
    }

    /**
     * Set the data.
     * 
     * @param   iterable        $data   Data to set.
     * @return  TableInterface
     */
    public function setData(iterable $data): TableInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Render the head.
     * 
     * @return string
     */
    public function renderHdr()
    {
        $thead = new Html($this->headTag);
        $tr = new Html($this->rowTag);

        $ret = '';

        foreach($this->columns as $k => $v) {
            if (!$v->isHidden()) {
                $ret .= $v->renderHdr();
            }
        }

        return $thead->render($tr->render($ret));
    }

    /**
     * Render the body.
     * 
     * @return string
     */
    public function renderBody()
    {
        $tbody = new Html($this->bodyTag);
        $tr = new Html($this->rowTag);

        $ret = '';

        foreach ($this->data as $row) {
            $trData = '';
            $rowData = null;
            if (is_object($row)) {
                $rowData = $row->asArray();
            } else {
                $rowData = $row;
            }
            foreach($this->columns as $k => $v) {
                if (!$v->isHidden()) {
                    if (is_array($rowData[$k])) {
                        $trData .= $v->renderBody(strval(array_values($rowData[$k])[0]));
                    } else {
                        $trData .= $v->renderBody(strval($rowData[$k]));
                    }
                }
            }
            $ret .= $tr->render($trData);
        }

        return $tbody->render($ret);
    }

    /**
     * Render the table.
     * 
     * @return  string
     */
    public function render(): string
    {
        $params = $this->params;
        if ($this->class) {
            $params['class'] = $this->class;
        }
        $table = new Html($this->tableTag, $params);

        $ret = $table->render($this->renderHdr() . $this->renderBody());

        if ($this->hasSortableColumns) {
            $fparams = array(
                'name'  =>  $this->name . '-form',
                'class' =>  'tableform tableform-' . $this->name
            );
            $f = new Html('form', $fparams);
            $ret = $f->render($ret);
        }

        return $ret;
    }

}
