<?php

/**************************************************
 * PHP DATABASE INTERFACE
 **************************************************/

/**************************************************
 * @package phpDBI
 * @subpackage controller
 **************************************************/

/**************************************************
 * @author: Roman Matthias Keil
 * @copyright: Roman Matthias Keil
 **************************************************/

/**************************************************
 * $Id: Connection.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/controller/Connection.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.controller.Host');
Application::import('core.database.controller.Credential');
Application::import('core.database.exception.*');

class Connection {

	private $connection;
	private $logger;

	/**
	 * @param Host $_host
	 * @param string $_database
	 * @param Credential $_credential
	 * @param Logger $_logger
	 */
	function __construct(Host $_host, $_database, Credential $_credential, Logger $_logger = null) {
		$this->logger = $_logger;

		$this->connection = mysql_connect($_host->getHost(), $_credential->getUsername(), $_credential->getPassword(), true);
		if(!$this->connection) throw new DatabaseException(mysql_error($this->connection));

		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('mysql_connect: '.$_host->getHost());

		$selected = mysql_select_db($_database, $this->connection);
		if (!$selected) throw new DatabaseException(mysql_error($this->connection));

		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('mysql_select_db: '.$_database);
	}

	/**
	 */
	function __destruct() {
		mysql_close($this->connection);

		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('mysql_close: ');

		$this->connection = null;
	}

	/**
	 * send an sql statement to the database engine
	 * @param string $_sql
	 * @return resource
	 */
	public function send($_sql) {
		$result = mysql_query($_sql, $this->connection);

		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('mysql_query: '.$_sql);

		if (!$result) throw new SQLStatementException('SQLStatementException: '.mysql_error($this->connection));
		return $result;
	}

	/**
	 * escape an sql value
	 * @param string $_sql
	 * @return string
	 */
	public function escape($_sql) {
		$result =  mysql_real_escape_string($_sql, $this->connection);

		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('mysql_real_escape_string: '.$_sql);

		if (!isset($result)) throw new SQLStatementException('SQLStatementException: '.mysql_error($this->connection));
		return $result;
	}

	/**
	 * get the createt index during an inseration
	 * @return number
	 */
	public function getInsertID() {
		$result = mysql_insert_id($this->connection);

		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('mysql_insert_id: '.$result);

		if (!$result) throw new SQLStatementException('SQLStatementException: '.mysql_error($this->connection));
		return $result;
	}

	/**
	 * get the number of affected rows by the last statement
	 * @return number
	 */
	public function getAffectedRows() {
		$result = mysql_affected_rows($this->connection);

		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('mysql_affected_rows: '.$result);

		if (empty($result) && ($result!=0)) throw new DatabaseException(mysql_error($this->connection));
		return $result;
	}

	/**
	 * get the number of rows in an sql result
	 * @param resource $_result
	 * @return number
	 */
	public function getNumRows($_result) {
		return mysql_num_rows($_result);
	}

	/**
	 * get the number of fields in an sql result
	 * @param resource $_result
	 * @return number
	 */
	public function getNumFields($_result) {
		return mysql_num_fields($_result);
	}
}
?>