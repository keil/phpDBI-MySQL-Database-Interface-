<?php

/**************************************************
 * PHP DATABASE INTERFACE
 **************************************************/

/**************************************************
 * @package phpDBI
 * @subpackage adapter
 **************************************************/

/**************************************************
 * @author: Roman Matthias Keil
 * @copyright: Roman Matthias Keil
 **************************************************/

/**************************************************
 * $Id: RowAdapter.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/adapter/RowAdapter.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.controller.Connection');

/**
 * RowAdapter
 */
interface RowAdapter {
	
	/**
	 * @param Connection $_connection
	 * @param $_table
	 * @param $_primary
	 * @param $_index
	 * @param Logger $_logger = null
	 * @return unknown_type
	 */
	public function assign(Connection $_connection, $_table, $_primary, $_index, Logger $_logger = null);
}
?>