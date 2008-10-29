<?php
/**
 * Base class for filtering
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
abstract class UNL_UCBCN_Filter
{
    /**
     * the controller instance running this output object
     */
    protected $controller;
    
    public function __construct(UNL_UCBCN $controller)
    {
        $this->controller = $controller;
    }
    
    /**
     * Performs a filter operation on the text, and returns the filtered text.
     * 
     * @param string $input
     */
    abstract public function filter($input);
}
?>