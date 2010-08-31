<?php

/**************************************************
 * PHP DATABASE INTERFACE
 **************************************************/

/**************************************************
 * @package phpDBI
 **************************************************/

/**************************************************
 * @author: Roman Matthias Keil
 * @copyright: Roman Matthias Keil
 **************************************************/

/**************************************************
 * $Id: DatabaseFactory.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/DatabaseFactory.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

class DatabaseFactory {

	private static $database;

	/**
	 * @param string $_name
	 * @param Database $_database
	 */
	public static function add($_name, Database $_database) {
		DatabaseFactory::$database[$_name] = $_database;
	}

	/**
	 * @param string $_name
	 * @return Database
	 */
	public static function get($_name) {
		if(isset(DatabaseFactory::$database[$_name]))
			return DatabaseFactory::$database[$_name];
		else
			throw new ErrorException('database not in use');
	}
}
?>
