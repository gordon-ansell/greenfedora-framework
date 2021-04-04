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
namespace GreenFedora\Db\Sql;

use GreenFedora\Db\DbInterface;

/**
 * Abstract SQL class.
 */
abstract class AbstractSql
{
    /**
     * Database parent.
     * @var DbInterface
     */
    protected $db = null;

    /**
     * Constructor.
     *
     * @param   DbInterface      $db         Database parent.
     * @return  void
     */
    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }
}
