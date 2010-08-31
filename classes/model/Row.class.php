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
 * $Id: Row.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/model/Row.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.controller.Connection');
Application::import('core.database.exception.*');

/**
 * Row
 */
class Row {

	/**
	 * @var string
	 */
	private $primary;
	/**
	 * @var string
	 */
	private $index;
	/**
	 * @var string
	 */
	private $table;

	/**
	 * @var Connection
	 */
	private $connection;
	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * @param Connection $_connection
	 * @param string $_table
	 * @param string $_primary
	 * @param string $_index
	 * @param Logger $_logger
	 */
	function __construct(Connection $_connection, $_table, $_primary, $_index, Logger $_logger = null) {
		$this->connection = $_connection;
		$this->logger = $_logger;
		$this->table = $_table;
		$this->primary = $_primary;
		$this->index = $_index;
	}

	/**
	 * get<FIELD>();
	 * set<FIELD>(<VALUE>)
	 * @param string $_call
	 * @param string $_arguments
	 * @return string
	 */
	public function __call($_call, $_arguments) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('_call: '.$_call);

		$cmd = substr($_call, 0, 3);
		$data = strtolower(substr($_call, 3));

		if(empty($data)) throw new UndefinedFieldException('null');

		if(!isset($data))
		throw new UnknownDatabaseException('wrong argument call');

		switch ($cmd) {
			case 'get':
				return $this->select($data);
				break;
			case 'set':
				if(count($_arguments)!=1)
				throw new UnknownDatabaseException('wrong argument call');
				return $this->update($data, $_arguments[0]);
				break;
			default:
				throw new UnknownDatabaseException('unknown function call');
				break;
		}
	}

	//////////////////////////////
	// SELECT
	//////////////////////////////

	/**
	 * @param string $_data
	 * @return string
	 */
	public function select($_data) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('select: '.$_data);
		if(empty($_data)) throw new UndefinedFieldException('null');

		$data = $this->connection->escape($_data);
		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		$index = $this->connection->escape($this->index);

		$sql = 'SELECT `'.$data.'` FROM `'.$table.'` WHERE `'.$primary.'` = \''.$index.'\';';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result)<0)
		throw new UndefinedRowException('undefined '.$primary.'='.$index);

		$values = mysql_fetch_assoc($result);
		return $values[$_data];
	}

	/**
	 * @param array $_data
	 * @return array
	 */
	public function selectValues(array $_data) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('selectValues: '.print_r($_data, true));
		if(empty($_data)) throw new UndefinedFieldException('null');

		$value = '';
		foreach($_data as $field)
		$value .= ',`'.$this->connection->escape($field).'`';
		$data = substr($value, 1);

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		$index = $this->connection->escape($this->index);

		$sql = 'SELECT '.$data.' FROM `'.$table.'` WHERE `'.$primary.'` = \''.$index.'\';';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result)<0)
		throw new UndefinedRowException('undefined '.$primary.'='.$index);

		$values = mysql_fetch_assoc($result);
		return $values;
	}

	/**
	 * @return array
	 */
	public function selectAll() {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('selectAll');

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		$index = $this->connection->escape($this->index);

		$sql = 'SELECT * FROM `'.$table.'` WHERE `'.$primary.'` = \''.$index.'\';';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result)<0)
		throw new UndefinedRowException('undefined '.$primary.'='.$index);

		$values = mysql_fetch_assoc($result);
		return $values;
	}

	////////////////////////////////////////
	// UPDATE
	////////////////////////////////////////

	/**
	 * @param string $_data
	 * @param string $_value
	 */
	public function update($_data, $_value) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('update: '.$_data);
		if(empty($_data)) throw new UndefinedFieldException('null');

		$data = $this->connection->escape($_data);
		$value = $this->connection->escape($_value);
		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		$index = $this->connection->escape($this->index);

		$sql = 'UPDATE `'.$table.'` SET `'.$data.'` = \''.$value.'\' WHERE `'.$primary.'` = \''.$index.'\';';
		$result = $this->connection->send($sql);

		if($this->connection->getAffectedRows()<0)
		throw new UndefinedRowException('undefined '.$primary.'='.$index);

		if($_data == $this->primary) $this->index = $_value;
	}

	/**
	 * @param array $_value
	 */
	public function updateValues(array $_value) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('updateValues: '.print_r(array_keys($_value), true));
		if(empty($_value)) throw new UndefinedFieldException('null');

		$values = '';
		foreach($_value as $data => $value) {
			$values .= ',`'.$this->connection->escape($data).'` = \''.$this->connection->escape($value).'\'';
		}
		$values = substr($values, 1);

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		$index = $this->connection->escape($this->index);

		$sql = 'UPDATE `'.$table.'` SET '.$values.' WHERE `'.$primary.'` = \''.$index.'\';';
		$result =  $this->connection->send($sql);

		if($this->connection->getAffectedRows()<0)
		throw new UndefinedRowException('undefined '.$primary.'='.$index);

		if(key_exists($this->primary, $_value)) $this->index = $_value[$this->primary];
	}

	//////////////////////////////
	// ACCEPT
	//////////////////////////////

	/**
	 * @param RowAdapter $_adapter
	 * @return unknown_type
	 */
	public function accept(RowAdapter $_adapter) {
		return $_adapter->assign($this->connection, $this->table, $this->primary, $this->index);
	}
}
?>
