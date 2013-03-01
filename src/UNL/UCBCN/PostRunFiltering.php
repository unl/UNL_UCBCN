<?php
namespace UNL\UCBCN;

interface PostRunFiltering
{
    static function setReplacementData($field, $data);
    public function postRun($data);
}
