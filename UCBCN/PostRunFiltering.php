<?php
interface UNL_UCBCN_PostRunFiltering
{
    static function setReplacementData($field, $data);
    public function postRun($data);
}
