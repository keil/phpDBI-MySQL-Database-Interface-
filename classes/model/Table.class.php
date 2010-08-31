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
 * $Id: Table.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/model/Table.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.controller.Connection');
Application::import('core.database.exception.*');
Application::import('core.database.model.Row');

/**
 * Table
 */
class Table {

	/**
	 * @var string
	 */
	private $primary;
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
	 * @param Logger $_logger
	 */
	function __construct(Connection $_connection, $_table, $_primary, Logger $_logger = null) {
		$this->connection = $_connection;
		$this->logger = $_logger;
		$this->table = $_table;
		$this->primary = $_primary;
	}

	/**
	 * get<INDEX>()
	 * @param string $_call
	 * @param string $_arguments
	 * @return Row unknown_type
	 */
	public function __call($_call, $_arguments) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('_call: '.$_call);

		$cmd = substr($_call, 0, 3);
		$index = strtolower(substr($_call, 3));

		if(empty($index)) throw new UndefinedRowException('null');

		switch ($cmd) {
			case 'get':
				return $this->getRow($index);
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
	 * @param string $_index
	 * @return Row
	 */
	public function getRow($_index) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('getRow: '.$_index);
		if(empty($_index)) throw new UndefinedRowException('null');
		return new Row($this->connection, $this->table, $this->primary, $_index, $this->logger);
	}

	/**
	 * @param string $_index
	 * @return boolean
	 */
	public function exist($_index) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('isset: '.$_index);
		if(empty($_index)) throw new UndefinedRowException('null');

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		$index = $this->connection->escape($_index);

		$sql = 'SELECT * FROM `'.$table.'` WHERE `'.$primary.'` = \''.$index.'\';';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result) == 1)
			return true;
		else
			return false;
	}
	
	/**
	 * @param Condition $_condition
	 * @return array
	 */
	public function getRowCollection(Condition $_condition) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('getRowCollection: '.$_condition->toString());
		if(empty($_condition)) throw new UndefinedRowException('null');

		$table = $this->connection->escape($this->table);
		$key = $this->connection->escape($condition->getKey());
		$value = $this->connection->escape($condition->getValue());
		$condition = $this->connection->escape($condition->getCondition());

		$sql = 'SELECT * FROM `'.$table.'` WHERE `'.$key.'` '.$condition.' \''.$value.'\';';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result) < 0)
		throw new UndefinedRowException($key.' '.$condition.' '.$value);

		$values = array();
		while ($row = mysql_fetch_assoc($result)) {
			if(!empty($row[$this->primary]))
			return new Row($this->connection, $this->table, $this->primary, $row[$this->primary], $this->logger);
			else
			throw new UndefinedRowException($key.' '.$condition.' '.$value);
		}
		return $values;
	}

	/**
	 * @param array $_conditions
	 * @param string $_operation
	 * @return array
	 */
	public function getRowCollectionOf(array $_conditions, $_operation = 'AND') {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('getRowCollectionOf: '.print_r($_conditions, true));
		if(empty($_condition)) throw new UndefinedRowException('null');

		switch ($_operation) {
			case 'AND':
				$value = '';
				foreach($_conditions as $condition)
					$value .= ' AND `'.$this->connection->escape($condition->getKey()).'` '.$this->connection->escape($condition->getCondition()).' \''.$this->connection->escape($condition->getValue()).'\'';
				$condition = substr($value, 4);
				break;
			case 'OR':
				$value = '';
				foreach($_conditions as $condition)
				$value .= ' OR `'.$this->connection->escape($condition->getKey()).'` '.$this->connection->escape($condition->getCondition()).' \''.$this->connection->escape($condition->getValue()).'\'';
				$condition = substr($value, 3);
				break;
			default:
				throw new SQLStatementException($_operation);
				break;
		}

		$table = $this->connection->escape($this->table);

		$sql = 'SELECT * FROM `'.$table.'` WHERE '.$condition.';';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result)<0)
		throw new UndefinedRowException('undefined '.$primary.'='.$index);
			
		$values = array();
		while ($row = mysql_fetch_assoc($result)) {
			if(!empty($row[$this->primary]))
			return new Row($this->connection, $this->table, $this->primary, $row[$this->primary], $this->logger);
			else
			throw new UndefinedRowException('undefined '.print_r($_conditions, true));
		}
		return $values;
	}

	/**
	 * @return array
	 */
	public function getAll() {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('getAll');

		$table = $this->connection->escape($this->table);

		$sql = 'SELECT * FROM `'.$table.'`;';
		$result = $this->connection->send($sql);

		$values = array();
		while ($row = mysql_fetch_assoc($result)) {
			if(!empty($row[$this->primary]))
			return new Row($this->connection, $this->table, $this->primary, $row[$this->primary], $this->logger);
			else
			throw new UndefinedRowException('empty result');
		}
		return $values;
	}

	//////////////////////////////
	// SELECT
	//////////////////////////////

	/**
	 * @param Condition $_condition
	 * @return array
	 */
	public function select(Condition $_condition) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('select: '.$_condition->toString());
		if(empty($_condition)) throw new UndefinedRowException('null');

		$table = $this->connection->escape($this->table);
		$key = $this->connection->escape($_condition->getKey());
		$value = $this->connection->escape($_condition->getValue());
		$condition = $this->connection->escape($_condition->getCondition());

		$sql = 'SELECT * FROM `'.$table.'` WHERE `'.$key.'` '.$_condition.' \''.$value.'\';';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result)<0)
		throw new UndefinedRowException('undefined '.$key.' '.$_condition.' '.$value);

		$values = array();
		while ($value = mysql_fetch_assoc($result))
			$values[] = $value;
		return $values;
	}

	/**
	 * @param array $_conditions
	 * @param string $_operation
	 * @return array
	 */
	public function selectOf(array $_conditions, $_operation = 'AND') {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('selectOf: '.print_r($_conditions, true));
		if(empty($_conditions)) throw new UndefinedRowException('null');

		switch ($_operation) {
			case 'AND':
				$value = '';
				foreach($_conditions as $condition)
					$value .= ' AND `'.$this->connection->escape($condition->getKey()).'` '.$this->connection->escape($condition->getCondition()).' \''.$this->connection->escape($condition->getValue()).'\'';
				$condition = substr($value, 4);
				break;
			case 'OR':
				$value = '';
				foreach($_conditions as $condition)
					$value .= ' OR `'.$this->connection->escape($condition->getKey()).'` '.$this->connection->escape($condition->getCondition()).' \''.$this->connection->escape($condition->getValue()).'\'';
				$condition = substr($value, 3);
				break;
			default:
				throw new SQLStatementException($_operation);
				break;
		}

		$table = $this->connection->escape($this->table);

		$sql = 'SELECT * FROM `'.$table.'` WHERE '.$condition.';';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result)<0)
		throw new UndefinedRowException('undefined '.print_r($_conditions, true));
	
		$values = array();
		while ($value = mysql_fetch_assoc($result))
			$values[] = $value;
		return $values;
	}

	/**
	 * @return array
	 */
	public function selectAll() {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('selectAll');

		$table = $this->connection->escape($this->table);

		$sql = 'SELECT * FROM `'.$table.'`;';
		$result = $this->connection->send($sql);

		$values = array();
		while ($value = mysql_fetch_assoc($result))
			$values[] = $value;
		return $values;
	}

	/**
	 * @param Condition $_condition
	 * @return array
	 */
	public function selectDesc(Condition $_condition) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('select: '.$_condition->toString());
		if(empty($_condition)) throw new UndefinedRowException('null');

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		$key = $this->connection->escape($_condition->getKey());
		$value = $this->connection->escape($_condition->getValue());
		$condition = $this->connection->escape($_condition->getCondition());

		$sql = 'SELECT * FROM `'.$table.'` WHERE `'.$key.'` '.$_condition.' \''.$value.'\' ORDER BY `'.$primary.'` DESC;';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result)<0)
		throw new UndefinedRowException('undefined '.$key.' '.$_condition.' '.$value);

		$values = array();
		while ($value = mysql_fetch_assoc($result))
			$values[] = $value;
		return $values;
	}

	/**
	 * @param array $_conditions
	 * @param string $_operation
	 * @return array
	 */
	public function selectOfDesc(array $_conditions, $_operation = 'AND') {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('selectOf: '.print_r($_conditions, true));
		if(empty($_conditions)) throw new UndefinedRowException('null');

		switch ($_operation) {
			case 'AND':
				$value = '';
				foreach($_conditions as $condition)
					$value .= ' AND `'.$this->connection->escape($condition->getKey()).'` '.$this->connection->escape($condition->getCondition()).' \''.$this->connection->escape($condition->getValue()).'\'';
				$condition = substr($value, 4);
				break;
			case 'OR':
				$value = '';
				foreach($_conditions as $condition)
					$value .= ' OR `'.$this->connection->escape($condition->getKey()).'` '.$this->connection->escape($condition->getCondition()).' \''.$this->connection->escape($condition->getValue()).'\'';
				$condition = substr($value, 3);
				break;
			default:
				throw new SQLStatementException($_operation);
				break;
		}

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);

		$sql = 'SELECT * FROM `'.$table.'` WHERE '.$condition.' ORDER BY `'.$primary.'` DESC;';
		$result = $this->connection->send($sql);

		if(mysql_num_rows($result)<0)
		throw new UndefinedRowException('undefined '.print_r($_conditions, true));
		


		$values = array();
		while ($value = mysql_fetch_assoc($result))
			$values[] = $value;
		return $values;
	}

	/**
	 * @return array
	 */
	public function selectAllDesc() {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('selectAll');

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		
		$sql = 'SELECT * FROM `'.$table.'` ORDER BY `'.$primary.'` DESC;';
		$result = $this->connection->send($sql);

		$values = array();
		while ($value = mysql_fetch_assoc($result))
			$values[] = $value;
		return $values;
	}
	
	
	//////////////////////////////
	// UPDATE
	//////////////////////////////

	/**
	 * @param array $_rows
	 */
	public function update(array $_rows) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('update');
		if(empty($_row)) throw new UndefinedException('null');

		foreach ($_rows as $row) {
			$id = $row[$this->primary];
			$values = '';
			foreach($row as $data => $value) {
				$values .= ',`'.$this->connection->escape($data).'` = \''.$this->connection->escape($value).'\'';
			}
			$values = substr($values, 1);

			$table = $this->connection->escape($this->table);
			$primary = $this->connection->escape($this->primary);
			$index = $this->connection->escape($id);

			$sql = 'UPDATE `'.$table.'` SET '.$values.' WHERE `'.$primary.'` = \''.$index.'\';';

			$result =  $this->connection->send($sql);

			if($this->connection->getAffectedRows()<0)
			throw new UndefinedRowException('undefined '.$primary.'='.$index);
		}
	}

	//////////////////////////////
	// INSERT
	//////////////////////////////

	/**
	 * @param array $_value
	 * @return string
	 */
	public function insert(array $_value) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('insert');
		if(empty($_value)) throw new UndefinedException('null');

		// HANDLING MIT NULL WERTEN
		// AUCH IN UPDATE

		$data = '';
		$values = '';

		foreach($_value as $key => $value) {
			$data .= ',`'.$this->connection->escape($key).'`';
			if($value == 'NULL') $values .= ',NULL';
			else $values .= ',\''.$this->connection->escape($value).'\'';
		}
		$data = substr($data, 1);
		$values = substr($values, 1);

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);

		if(array_key_exists($primary, $_value) || empty($primary)) $sql = 'INSERT INTO `'.$table.'` ('.$data.') VALUES ('.$values.');';
		else $sql = 'INSERT INTO `'.$table.'` (`'.$primary.'`, '.$data.') VALUES (NULL, '.$values.');';
		
		$result = $this->connection->send($sql);

		// bedingungen prüfen
		// undefined row field table ..
		// fehlermeldungen verschönern
		if($this->connection->getAffectedRows()<=0)
			throw new SQLStatementException('invalid statement '.$sql);

		if(array_key_exists($primary, $_value)) return $_value[$this->primary];
		else if(empty($primary)) return 0;
		else return $this->connection->getInsertID();
	}

	//////////////////////////////
	// DELETE
	//////////////////////////////

	/**
	 * @param string $_index
	 */
	public function delete($_index) {
		/* ## LOGGER ## */ if(isset($this->logger)) $this->logger->DEBUG('delete');
		if(empty($_index)) throw new UndefinedRowException('null');

		$table = $this->connection->escape($this->table);
		$primary = $this->connection->escape($this->primary);
		$index = $this->connection->escape($_index);

		$sql = 'DELETE FROM `'.$table.'` WHERE `'.$primary.'` = \''.$index.'\';';
		$result = $this->connection->send($sql);

		if($this->connection->getAffectedRows()<=0)
		throw new UndefinedRowException('undefined '.$primary.'='.$index);
	}

	//////////////////////////////
	// ACCEPT
	//////////////////////////////

	/**
	 * @param TableAdapter $_adapter
	 * @return unknown_type
	 */
	public function accept(TableAdapter $_adapter) {
		return $_adapter->assign($this->connection, $this->table, $this->primary, $this->index);
	}
}
?>
