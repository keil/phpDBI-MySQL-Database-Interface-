<?php

/**************************************************
 * PHP DATABASE INTERFACE
 **************************************************/

/**************************************************
 * @package phpDBI
 * @subpackage exception
 **************************************************/

/**************************************************
 * @author: Roman Matthias Keil
 * @copyright: Roman Matthias Keil
 **************************************************/

/**************************************************
 * $Id: UndefinedException.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/exception/UndefinedException.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.exception.DatabaseException');

/**
 * UndefinedException
 */
class UndefinedException extends DatabaseException {
	/**
	 * @param string $message
	 * @param number $code
	 * @param Exception $previous
	 */
	function __construct($message = null, $code = 0, Exception $previous = null) {
		parent::__construct('undefined exception: '.$message, $code, $previous);
	}
}
?>