<?php
namespace UNL\UCBCN\ActiveRecord\Record;

use UNL\UCBCN\ActiveRecord\Exception as Exception;

class PostHandler
{
    /**
     * Class name for the record to handle
     *
     * @var string
     */
    protected $class;

    /**
     * Posted array data! e.g. $_POST
     *
     * @var array
     */
    protected $post = array();

    /**
     * The record we're handling
     *
     * @var \UNL\UCBCN\ActiveRecord\Record
     */
    protected $record;

    public function __construct($class, $post = array())
    {
        if (!is_subclass_of($class, __NAMESPACE__)) {
            throw new Exception('Invalid record class.', 400);
        }

        $this->class = $class;
        $this->post  = $post;
    }

    /**
     * Handle the data posted and save the record
     *
     * @return \UNL\UCBCN\ActiveRecord\Record | false on error
     */
    public function handle()
    {
        $this->record = new $this->class;

        $this->record->synchronizeWithArray($this->post);

        if ($result = $this->record->save()) {
            return $this->record;
        }

        return $result;

    }
}
