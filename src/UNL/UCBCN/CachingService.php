<?php
interface UNL_UCBCN_CachingService
{
    public function get($key);
    public function save($data, $key);
    public function clean($object = null);
}