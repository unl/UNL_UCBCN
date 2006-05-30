<?php
require_once 'PEAR.php'
/**
 * UNL UCBCN error handler, loaded on demand... based on the DB_DataObject_Error code.
 *
 * UNL_UCBCN_Error is a quick wrapper around pear error, so you can distinguish the
 * error code source.
 *
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @package    UNL_UCBCN
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 */

class UNL_UCBCN_Error extends PEAR_Error
{
	/**
	 * DB_DataObject_Error constructor.
	 *
	 * @param mixed   $code   Error code, or string with error message.
	 * @param integer $mode   what "error mode" to operate in
	 * @param integer $level  what error level to use for $mode & PEAR_ERROR_TRIGGER
	 * @param mixed   $debuginfo  additional debug info, such as the last query
	 *
	 * @access public
	 *
	 * @see PEAR_Error
	 */
	function __construct($message = '', $code = DB_ERROR, $mode = PEAR_ERROR_RETURN,
			$level = E_USER_NOTICE)
	{
		$this->PEAR_Error('DB_DataObject Error: ' . $message, $code, $mode, $level);
	}
}
