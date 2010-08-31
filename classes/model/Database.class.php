<?php

/**************************************************
 * PHP DATABASE INTERFACE
 **************************************************/

/**************************************************
 * @package phpDBI
 * @subpackage model
 **************************************************/

/**************************************************
 * @author: Roman Matthias Keil
 * @copyright: Roman Matthias Keil
 **************************************************/

/**************************************************
 * $Id: Database.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/model/Database.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.controller.*');
Application::import('core.database.exception.*');
Application::import('core.database.model.Table');

/**
 * Database
 */
class Database {

	/**
	 * @var Connection
	 */
	private $connection;
	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @param Host $_host
	 * @param string $_database
	 * @param Credential $_credential
	 * @param Logger $_logger
	 */
	function __construct(Host $_host, $_database, Credential $_credential, Logger $_logger = null) {
		$this->logger = $_logger;
		$this->connection = new Connection($_host, $_database, $_credential, $_logger);
	}

	/**
	 * get<TABLE>()
	 * @param string $_call
	 * @param string $_arguments
	 * @return Table
	 */
	public function __call($_call, $_arguments) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('_call: '.$_call);
		
		$cmd = substr($_call, 0, 3);
		$table = strtolower(substr($_call, 3));

		if(empty($table)) throw new UndefinedTabelException('null');
		
		switch ($cmd) {
			case 'get':
				return $this->getTable($table);
				break;
			default:
				throw new UnknownDatabaseException('unknown function call');
				break;
		}
	}

	//////////////////////////////
	// GET
	//////////////////////////////

	/**
	 * @param string $_table
	 * @return Table
	 */
	public function getTable($_table) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('getTable: '.$_table);
		if(empty($_table)) throw new UndefinedTabelException('null');
		
		$table = $this->connection->escape($_table);

		$sql = 'SHOW KEYS FROM `'.$table.'`;';
		$result = $this->connection->send($sql);
		$values = mysql_fetch_assoc($result);

		// TODO funktioniert das wirklich
		if(mysql_num_rows($result)<0)
		throw new UndefinedTableException('undefined primary');

		$primary = $values['Column_name'];
		return new Table($this->connection, $_table, $primary, $this->logger);
	}

	//////////////////////////////
	// TRUNCATE
	//////////////////////////////

	/**
	 * @param string $_table
	 */
	public function truncate($_table) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('truncate: '.$_table);
		if(empty($_table)) throw new UndefinedTabelException('null');

		$table = $this->connection->escape($_table);
		$sql = 'TRUNCATE TABLE `'.$table.'`';
		$result = $this->connection->send($sql);
	}

	//////////////////////////////
	// ACCEPT
	//////////////////////////////

	/**
	 * @param DatabaseAdapter $_adapter
	 * @return unknown_type
	 */
	public function accept(DatabaseAdapter $_adapter) {
		return $_adapter->assign($this->connection, $this->table, $this->primary, $this->index);
	}
}
?>