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
     * @var \Traversable
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
     * Constructor.
     * 
     * @param   string|null     $class       Class.
     * @param   array           $params      Parameters.
     * @return  void
     */
    public function __construct(?string $class = null, array $params = [])
    {

        $this->class = $class;
        $this->params = $params;
    }

    /**
     * Add a column.
     * 
     * @param   string|ColumnInterface      $title          Column title or instance.
     * @param   string|null                 $hdrClass       Column header class.
     * @param   string|null                 $bodyClass      Column body class.
     * @param   array                       $hdrParams      Header parameters.
     * @param   array                       $bodyParams     Body parameters.
     * @return  TableInterface
     */
    public function addColumn($title = '', ?string $hdrClass = null, 
        ?string $bodyClass = null, array $hdrParams = [], array $bodyParams = []): TableInterface
    {
        if ($title instanceof ColumnInterface) {
            $this->columns[] = $title;
            return $this;
        }

        $this->columns[] = new Column($this, $title, $hdrClass, $bodyClass, $hdrParams, $bodyParams);
        return $this;
    }

    /**
     * Get a column.
     * 
     * @param   int     $index      Column index, 1-based.
     * @return  ColumnInterface
     * @throws  InvalidArgumentException
     */
    public function getColumn(int $index): ColumnInterface
    {
        if ($this->columns[$index - 1]) {
            return $this->columns[$index - 1];
        }
        throw new InvalidArgumentException(sprintf("No column with index '%s' found.", strval($index - 1)));
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
     * @param   \Traversable    $data   Data to set.
     * @return  TableInterface
     */
    public function setData(\Traversable $data): TableInterface
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
            $ret .= $v->renderHdr();
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
            foreach($this->columns as $k => $v) {
                $ret .= $tr.render($v->renderBody($row[$k]));
            }
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
            $params['class'] = $this->params;
        }
        $table = new Html($this->tableTag, $params);

        $ret = $table->render($this->renderHdr() . $this->renderBody());
        return $ret;
    }

}
