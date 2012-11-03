<?php

namespace UNL\UCBCN\ActiveRecord\Record;

use UNL\UCBCN\Exception as Exception;

/**
 * This class is used to generate a generic edit form for a Record
 *
 *
 * @author Brett Bieber
 */
class EditForm
{
    /**
     * The Record object
     *
     * @var Record $record
     */
    protected $record;

    public function __construct($options = array())
    {
        if (isset($options['table'])) {
            // Try and guess the model name from the table

            // Set the base namespace
            $options['class'] = substr(__NAMESPACE__, 0, strpos(__NAMESPACE__, '\\', 1));

            // Remove intermediate parent object ids
            $options['table'] = preg_replace('/[\d]+\//', '', $options['table']);

            // Convert table name to singular model name
            $options['class'] .= '\\'.trim(str_replace(' ', '\\', ucwords(str_replace('/', ' ', $options['table']))), 's');

        }

        if (!isset($options['class'])) {
            throw new Exception('You must specify a class name', 400);
        }

        if (!is_subclass_of($options['class'], __NAMESPACE__)) {
            throw new Exception('Invalid record class.', 400);
        }

        $this->record = call_user_func_array(array($options['class'], 'getById'), array($options['id']));

        if (!$this->record) {
            throw new Exception('Invalid record id specified', 404);
        }

    }

    /**
     * Get the Record object we're editing
     *
     * @return Record
     */
    public function getRecord()
    {
        return $this->record;
    }
}
