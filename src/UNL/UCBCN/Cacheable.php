<?php
namespace UNL\UCBCN;

interface Cacheable
{
    public function getCacheKey();
    public function run();
    public function preRun($cache_hit = false);
}
