<?php

/**************************************************
 * PHP DATABASE INTERFACE
 **************************************************/

/**************************************************
 * @package phpDBI
 * @subpackage condition
 **************************************************/

/**************************************************
 * @author: Roman Matthias Keil
 * @copyright: Roman Matthias Keil
 **************************************************/

/**************************************************
 * $Id: Condition.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/condition/Condition.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

/**
 * Condition
 */
abstract class Condition {

	/**
	 * @var $key
	 */
	private $key;
	/**
	 * @var $condition
	 */
	private $condition;
	/**
	 * @var $value
	 */
	private $value;

	/**
	 * @param $_key
	 * @param $_condition
	 * @param $_value
	 */
	function __construct($_key, $_condition, $_value) {
		$this->key = $_key;
		$this->condition = $_condition;
		$this->value = $_value;
	}

	/**
	 * get the key field
	 * @return $this->key
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * get the operation [EQUALS, NOT, LIKE]
	 * @return $this->condition
	 */
	public function getCondition() {
		return $this->condition;
	}

	/**
	 * get the value field
	 * @return $this->value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * return an string like <key> <operation> <value>
	 * @return string
	 */
	public function toString() {
		return $this->key.' '.$this->condition.' '.$this->value;
	}
}
?>