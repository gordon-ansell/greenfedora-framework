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

use GreenFedora\Db\Sql\AbstractSql;
use GreenFedora\Db\Sql\UpdateInterface;

use GreenFedora\Db\Driver\Stmt\StmtInterface;

use GreenFedora\Db\Sql\Part\TableName;
use GreenFedora\Db\Sql\Part\ColumnNameCollection;
use GreenFedora\Db\Sql\Part\ValueCollection;
use GreenFedora\Db\Sql\Part\WhereUser;
use GreenFedora\Db\Sql\Part\Where;
use GreenFedora\Db\Sql\Part\LimitUser;
use GreenFedora\Db\Sql\Part\Limit;

use GreenFedora\Db\DbInterface;

/**
 * SQL update class interface.
 */
interface UpdateInterface
{
    /**
     * Get the SQL.
     *
     * @return  string
     */
    public function getSql() : string;

    /**
     * Prepare.
     * 
     * @return  StmtInterface
     */
    public function prepare() : ?StmtInterface;

    /**
     * Execute.
     *
     * @return  bool
     */
    public function execute();
}
