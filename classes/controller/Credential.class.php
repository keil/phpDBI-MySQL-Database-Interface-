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
 * $Id: Credential.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/controller/Credential.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

/**
 * @author Matthias
 *
 */
class Credential {

	/**
	 * @var $username
	 */
	private $username;
	/**
	 * @var $password
	 */
	private $password;

	/**
	 * @param $_username
	 * @param $_password
	 */
	function __construct($_username, $_password) {
		$this->username = $_username;
		$this->password = $_password;
	}

	/**
	 * get the username
	 * @return $this->username
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * get the password
	 * @return $this->password
	 */
	public function getPassword() {
		return $this->password;
	}
}
?>