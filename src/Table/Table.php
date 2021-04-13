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

        $this->columns[] = new Column($title, $hdrClass, $bodyClass, $hdrParam, $bodyParams);
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
     * Render the table.
     * 
     * @return  string
     */
    public function render(): string
    {
        $ret = '';
        return $ret;
    }

}
