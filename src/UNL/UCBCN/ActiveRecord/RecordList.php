<?php
namespace UNL\UCBCN\ActiveRecord;

abstract class RecordList extends \LimitIterator implements \Countable
{

    //By default, do not limit.
    public $options = array('limit'=>-1, 'offset'=>0);

    /**
     * Get the defualt options for the list class
     * Required to set the following:
     *     $options['listClass'] the class of the list.
     *     $options['itemClass'] the class of each item in the list.
     * @return array
     */
    abstract public function getDefaultOptions();

    /*
     * @param $options Requires Listclass and ItemClass to work properlly.
     *
     *     listClass: The class name of the list.
     *     itemClass: The class name of the items in the list.
     */
    public function __construct($options = array())
    {
        $this->options = $options + $this->options;

        $this->options = $this->options + $this->getDefaultOptions();

        if (!isset($this->options['listClass'])) {
            throw new Exception("No List Class was set", 500);
        }

        if (!isset($this->options['itemClass'])) {
            throw new Exception("No Item Class was set", 500);
        }

        if (!isset($this->options['array'])) {
            //get a lit of all of them by default.
            $this->options['array'] = $this->getAllForConstructor();
        }

        $list = new \ArrayIterator($this->options['array']);

        parent::__construct($list, $this->options['offset'], $this->options['limit']);
    }

    protected function getAllForConstructor()
    {
        $options['sql']         = $this->getSQL();
        $options['returnArray'] = true;

        return $this->getBySQL($options);
    }

    /**
     * Get the default SQL for this list
     *
     * @return string
     */
    protected function getSQL()
    {
        return $this->getSelectClause(). ' '
               . $this->getFromClause() . ' '
               . $this->getWhereClause() . ' '
               . $this->getOrderByClause();
    }

    /**
     * Get the SELECT portion of the query
     *
     * @return string
     */
    protected function getSelectClause()
    {
        $table = call_user_func(array($this->options['itemClass'], 'getTable'));

        return 'SELECT '.Database::getDB()->escape_string($table).'.id ';
    }

    /**
     * Get the list of tables used in the SELECT query
     *
     * @return string
     */
    protected function getFromClause()
    {
        $table = call_user_func(array($this->options['itemClass'], 'getTable'));

        return ' FROM ' . Database::getDB()->escape_string($table);
    }

    /**
     * Get the WHERE clause for the default SQL
     *
     * @return string
     */
    protected function getWhereClause()
    {
        return '';
    }

    /**
     * The order by declaraction used in the SQL
     *
     * @return string
     */
    protected function getOrderByClause()
    {
        return '';
    }

     /**
     * generate a list by sql.
     *
     * @param $options
     *        $options['sql'] = the sql string. (required)
     *        $options['listClass'] the class of the list. (optional (required if returning an iterator))
     *        $options['itemClass'] the class of each item in the list. (optional (required if returning an iterator))
     *        $options['returnArray'] return an array instead of an iterator. (optional).
     *
     * @return mixed
     */
    public static function getBySQL(array $options)
    {
        if (!isset($options['sql'])) {
            throw new Exception("options['sql'] was not set!", 500);
        }

        $mysqli           = Database::getDB();
        $options['array'] = array();

        if (!($result = $mysqli->query($options['sql']))) {
            throw new Exception($mysqli->errno.':'.$mysqli->error, 500);
        }

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $options['array'][] = $row;
        }

        if (isset($options['returnArray']) && $options['returnArray'] == true) {
            return $options['array'];
        }

        if (!isset($options['listClass'], $options['itemClass'])) {
            throw new Exception("options['listClass'] or options['itemClass'] were not set!", 500);
        }

        return new $options['listClass']($options);
    }

    public static function escapeString($string)
    {
        $mysqli = Database::getDB();

        return $mysqli->escape_string($string);
    }

    public function current()
    {
        return call_user_func_array($this->options['itemClass'] . "::getByID", parent::current());
    }

    public function count()
    {
        $iterator = $this->getInnerIterator();
        if ($iterator instanceof EmptyIterator) {
            return 0;
        }

        return count($this->getInnerIterator());
    }

}
