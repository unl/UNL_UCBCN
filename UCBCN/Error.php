<?php
/**
 * UNL UCBCN error handler, loaded on demand... based on the DB_DataObject_Error code.
 *
 * UNL_UCBCN_Error is a quick wrapper around pear error, so you can distinguish the
 * error code source.
 *
 *
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */

/**
 * Require the PEAR class to extend it for error handling.
 */
require_once 'PEAR.php';

/**
 * Extend PEAR_Error for error handling.
 * 
 * @package   UNL_UCBCN
 * @author    Brett Bieber <brett.bieber@gmail.com>
 * @copyright 2008 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://pear.unl.edu/
 */
class UNL_UCBCN_Error extends PEAR_Error
{
	/**
	 * UNL_UCBCN_Error constructor.
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
	function UNL_UCBCN_Error($message = '', $code = NULL, $mode = PEAR_ERROR_RETURN,
			$level = E_USER_NOTICE)
	{
		$this->PEAR_Error('UNL_UCBCN Error: ' . $message, $code, $mode, $level);
	}
}
