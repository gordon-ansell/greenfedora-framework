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
     * Constructor.
     * 
     * @param   TableInterface  $table          Parent table.
     * @param   string          $title          Column title.
     * @param   string|null     $hdrClass       Column header class.
     * @param   string|null     $bodyClass      Column body class.
     * @param   array           $hdrParams      Header parameters.
     * @param   array           $bodyParams     Body parameters.
     * @return  void
     */
    public function __construct(?string $class = null, array $params = [])
    {

        $this->class = $class;
        $this->params = $params;
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

}
