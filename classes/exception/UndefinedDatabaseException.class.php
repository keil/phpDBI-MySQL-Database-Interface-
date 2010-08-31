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
 * $Id: UndefinedDatabaseException.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/exception/UndefinedDatabaseException.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.exception.UndefinedException');

/**
 * UndefinedDatabaseException
 */
class UndefinedDatabaseException extends UndefinedException {
	/**
	 * @param string $database
	 * @param number $code
	 * @param Exception $previous
	 */
	function __construct($database = null, $code = 0, Exception $previous = null) {
		parent::__construct('undefined database: '.$database, $code, $previous);
	}
}
?>