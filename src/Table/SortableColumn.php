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

use GreenFedora\Table\Column;
use GreenFedora\Table\SortableColumnInterface;
use GreenFedora\Table\TableInterface;
use GreenFedora\Html\Html;

/**
 * Sortable table column.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class SortableColumn extends Column implements SortableColumnInterface
{
    /**
     * Column name.
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     * 
     * @param   TableInterface  $table          Parent table.
     * @param   string          $name           Column name.
     * @param   string          $title          Column title.
     * @param   string|null     $hdrClass       Column header class.
     * @param   string|null     $bodyClass      Column body class.
     * @param   array           $hdrParams      Header parameters.
     * @param   array           $bodyParams     Body parameters.
     * @return  void
     */
    public function __construct(TableInterface $table, string $name, string $title = '', ?string $hdrClass = null, 
        ?string $bodyClass = null, array $hdrParams = [], array $bodyParams = [])
    {
        $this->name = $name;
        parent::__construct($table, $title, $hdrClass, $bodyClass, $hdrParams, $bodyParams);
    }

    /**
     * Get the name.
     * 
     * @var string
     */
    public function getName(): string
    {
        return $this->name;
    }

}
