<?php
/**
 * This file parses the configuration and connection details for the calendar database.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2007 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */

/**
 * Require DB_DataObject before initializing the connection details.
 */
require_once 'DB/DataObject.php';

// Load database settings
$options = &PEAR::getStaticProperty('DB_DataObject','options');
$options = array(
    'database'         => '@DSN@',
    'schema_location'  => '@DATA_DIR@/UNL_UCBCN/DBDataObjects',
    'class_location'   => '@PHP_DIR@/UNL/UCBCN/DBDataObjects',
    'require_prefix'   => '@PHP_DIR@/UNL/UCBCN/DBDataObjects',
    'class_prefix'     => 'UNL_UCBCN_',
);