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
namespace GreenFedora\Db\Schema;

use GreenFedora\Db\Db;
use GreenFedora\Db\Schema\Table;
use GreenFedora\Db\Schema\Exception\DbSchemaException;
use GreenFedora\Db\Schema\Exception\InvalidArgumentException;

/**
 * Database schema.
 */
class Schema
{
    /**
     * Table specifications.
     * @var array
     */
    protected $tableSpec = array();

    /**
     * Tables.
     * @var Table[]
     */
    private $tables = array();

    /**
     * Install messages.
     * @var array
     */
    protected $msgs = array();

    /**
     * Constructor.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->init();
        $this->addTablesFromSpec();
    }

    /**
     * Initialisation.
     *
     * @return  void
     */
    protected function init()
    {
    }

    /**
     * Add tables from spec.
     *
     * @return  void
     */
    protected function addTablesFromSpec()
    {
        foreach ($this->tableSpec as $class => $tableName) {
            $this->addTable(new $class($this->getSm(), $this, $tableName));
        }
    }

    /**
     * Add a message.
     * 
     * @param   string      $msg            Message to add.
     * @return  void
     */
    public function addMsg(string $msg)
    {
        $this->msgs[] = $msg;
    }

    /**
     * Get all the mesages.
     * 
     * @return  array
     */
    public function getMsgs()
    {
        return $this->msgs;
    }

    /**
     * Auto the database.
     *
     * @param   bool        $forceNew       Force new install.
     * @return  bool
     * @throws  DbSchemaException
     */
    public function auto(bool $forceNew = false)
    {
        $this->addMsg('Auto install invoked');
        if ($forceNew) {
            $this->addMsg('Forcing new install');
        } else {
            $this->addMsg('Not forcing a new install');
        }

        // Check the code version.
        if (!$this->getConfig()->has('version')) {
            throw new DbSchemaException("Unable to auto-configure database - no code version in config");
        }
        $codeVersion = $this->getConfig()->get('version');
        $this->addMsg('Code version is: ' . $codeVersion);

        // Force new?
        if ($forceNew) {
            $this->addMsg('Dropping all tables');
            $this->dropAllTables();
            $sql = 'DROP TABLE IF EXISTS ' . $this->getDb()->tn('version');
            $this->getDb()->prepare($sql)->execute();
        }

        // Get the db version.
        $dbVersion = $this->schemaExists();
        if (!$dbVersion) {
            $this->addMsg('No db version found, creating all tables from scratch');
            $this->createAllTables();
            $this->addMsg('Updating db version to: ' . $codeVersion);
            $this->updateVersion($codeVersion);
        } else {
            $this->addMsg('Existing db version is: ' . $dbVersion);
            $methods = get_class_methods($this);
            $marr = array();
            foreach ($methods as $method) {
                if ('upgrade_' == substr($method, 0, 8)) {
                    $m = substr($method, 8);
                    if ((version_compare($m, $codeVersion) <= 0) and (version_compare($dbVersion, $m) == -1)) {
                        $marr[] = $method;
                    }
                }
            }

            if (empty($marr)) {
                $this->addMsg("No upgrades available");
            } else {
                usort($marr, array($this, 'verSort'));
                $this->addMsg("We need the following upgrades: " . implode(', ', $marr));
            }

            foreach ($marr as $method) {
                $this->addMsg('Running: ' . $method);
                $this->$method();
            }
        }
    }

    /**
     * Sort by version.
     * 
     * @param   string      $a      First.
     * @param   string      $b      Second.
     * @return  int
     */
    protected function verSort(string $a, string $b) : int
    {
        return version_compare(substr($a, 8), substr($b, 8));
    }

    /**
     * Upgrade.
     * 
     * @return void
     */
    //public function upgrade_0_0_1()
    //{
    //    $this->updateVersion('0.0.1');        
    //}

    /**
     * Upgrade.
     * 
     * @return void
     */
    //public function upgrade_0_0_0Dev2()
    //{
    //    $this->updateVersion('0.0.0Dev2');        
    //}

    /**
     * Upgrade.
     * 
     * @return void
     */
    //public function upgrade_0_0_0Dev3()
    //{
    //    $this->updateVersion('0.0.0Dev3');                
    //}


    /**
     * Update the version.
     *
     * @param   string      $version        Version to update.
     * @return  void
     */
    protected function updateVersion(string $version)
    {
        $dbVersion = $this->schemaExists();
        if ($dbVersion) {
            $this->getDb()->update('version', array('version' => $version))->execute();
        } else {
            $sql = 'CREATE TABLE ' . $this->getDb()->tn('version') . ' (' . $this->getDb()->idq('version') .
            ' VARCHAR(32))';
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute();

            $this->getDb()->insert('version', array('version' => $version))->execute();
        }
        $this->addMsg('Updated database to version: ' . $version);
    }

    /**
     * Create all tables.
     *
     * @return  void
     */
    public function createAllTables()
    {
        foreach ($this->tables as $table) {
            $table->create();
        }
    }

    /**
     * Drop all tables.
     *
     * @return  void
     */
    public function dropAllTables()
    {
        foreach (array_reverse($this->tables, true) as $table) {
            $table->drop();
        }
    }

    /**
     * Add a table.
     *
     * @param   string|Table    $table      Table name or obkect.
     * @param   array           $props      Table properties.
     * @return  Table
     */
    public function addTable($table, array $props = array()) : Table
    {
        if ($table instanceof Table) {
            $name = $table->getName();
            $this->tables[$name] = $table;
        } else {
            $name = $table;
            $this->tables[$name] = new Table($this->getSm(), $this, $name, $props);
        }
        return $this->tables[$name];
    }

    /**
     * Get a table.
     *
     * @param   string          $name           Table name.
     * @return  Table
     * @throws  InvalidArgumentException
     */
    public function getTable(string $name) : Table
    {
        if (array_key_exists($name, $this->tables)) {
            return $this->tables[$name];
        }
        throw new InvalidArgumentException(sprintf("Table '%s' does not exist", $name));
    }

    /**
     * Check to see if the schema exists.
     *
     * @return  string|false
     */
    public function schemaExists()
    {
        try {
            $result = $this->getDb()->select('version')->col('version')->fetchColumn();
        } catch (\Exception $e) {
            return false;
        }
        return $result;
    }
}
