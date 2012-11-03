<?php
namespace UNL\UCBCN\ActiveRecord\Record;

use UNL\UCBCN\ActiveRecord\RecordList;
use UNL\UCBCN\ActiveRecord\Exception;

class Search extends RecordList
{
    public function __construct($options = array())
    {
        if (!isset($options['listClass'])) {
            throw new Exception('You must pass a list class "listClass".', 400);
        }
        parent::__construct($options);
    }

    public function getDefaultOptions()
    {
        return array(
            'listClass' => $this->options['listClass'],
            'itemClass' => $this->options['itemClass'],
        );
    }

    public function getSQL()
    {
        $record = new $this->options['itemClass'];
        $table = $record->getTable();

        if (empty($this->options['fields'])) {
            return 'SELECT * FROM '.$table.' WHERE 0';
        }

        $sql = 'SELECT '.implode(',', $record->keys()).' FROM '.$table.' WHERE 1';

        $params = array_keys(get_class_vars($this->options['itemClass']));
        foreach ($params as $key=>$field) {

            switch ($this->options['func'][$key]) {
                case 'LIKE':
                case '=':
                case '!=':
                case '>':
                case '<':
                case '>=':
                case '<=':
                    if (empty($this->options['fields'][$key])) {
                        continue;
                    }
                    $sql .= ' AND `'.$this->escapeString($field).'` '.$this->options['func'][$key].' "'.$this->escapeString($this->options['fields'][$key]).'"';
                    break;
                case "= ''":
                case "!= ''":
                case 'IS NULL':
                case 'IS NOT NULL':
                    $sql .= ' AND `'.$this->escapeString($field).'` '.$this->options['func'][$key];
                    break;
            }
        }

        return $sql;
    }

}
