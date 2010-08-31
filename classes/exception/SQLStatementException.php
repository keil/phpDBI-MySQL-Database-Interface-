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
 * $Id: SQLStatementException.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/exception/SQLStatementException.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.exception.DatabaseException');

/**
 * SQLStatementException
 */
class SQLStatementException extends DatabaseException {
	/**
	 * @param string $statement
	 * @param number $code
	 * @param Exception $previous
	 */
	function __construct($statement = null, $code = 0, Exception $previous = null) {
		parent::__construct('invalid SQL statement: '.$statement, $code, $previous);
	}
}
?>