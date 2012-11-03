<?php
namespace UNL\UCBCN\Activerecord;

use UNL\UCBCN\ActiveRecord\Database;

/**
 *
 * Retrieves table information using SHOW FULL COLUMNS
 *
 * <?php
 * $info = new TableInfo('publishers');
 * echo $info->verified_url['Comment'];
 * ?>
 *
 * @author Brett Bieber <brett.bieber@gmail.com>
 *
 */
class TableInfo
{
    protected $_table;

    protected $_info;

    function __construct($table)
    {
        $this->_table = $table;
        $this->getTableInfo($this->_table);
    }

    function getTableInfo($table)
    {
        $db = Database::getDB();

        $info = $db->query('SHOW FULL COLUMNS FROM `'.$db->escape_string($table).'`;');

        while ($row = $info->fetch_assoc()) {
            $this->_info[$row['Field']] = $row;
        }
    }

    function __get($var)
    {
        if (!isset($this->_info[$var])) {
            throw new Exception('Invalid column specified: '.$this->_table.'.'.$var);
        }

        return $this->_info[$var];
    }
}