<?php
namespace UNL\UCBCN;

use UNL\UCBCN\ActiveRecord\RecordList;

class Permissions extends RecordList
{
    public function getDefaultOptions()
    {
        return array(
            'listClass' =>  __CLASS__,
            'itemClass' => __NAMESPACE__ . '\\Permission',
        );
    }
}