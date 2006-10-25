<?php
/**
 * This file parses the configuration and connection details for the calendar database.
 * 
 * @package UNL_UCBCN
 * @author bbieber
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