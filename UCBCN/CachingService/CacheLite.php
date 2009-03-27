<?php
require_once 'UNL/UCBCN/CachingService.php';
class UNL_UCBCN_CachingService_CacheLite implements UNL_UCBCN_CachingService
{
    protected $cache;
    
    function __construct()
    {
        include_once 'Cache/Lite.php';
        $this->cache = new Cache_Lite(array('lifeTime'=>null));
    }
    
    function get($key)
    {
        return $this->cache->get($key, 'UNL_UCBCN');
    }
    
    function save($data, $key)
    {
        return $this->cache->save($data, $key, 'UNL_UCBCN');
    }
    
    function clean($object = null)
    {
        if (isset($object)) {
            if (is_object($object)
                && $object instanceof UNL_UCBCN_Cacheable) {
                $key = $object->getCacheKey();
                if ($key === false) {
                    // This is a non-cacheable object.
                    return true;
                }
            } else {
                $key = (string) $object;
            }
            if ($this->cache->get($key) !== false) {
                // Remove the cache for this individual object.
                return $this->cache->remove($key, 'UNL_UCBCN');
            }
        } else {
            return $this->cache->clean('UNL_UCBCN');
        }
        return false;
    }

}
