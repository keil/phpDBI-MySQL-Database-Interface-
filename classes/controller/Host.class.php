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
 * $Id: Host.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/controller/Host.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

/**
 * Host
 */
class Host {

	/**
	 * @var $hostname
	 */
	private $hostname;
	/**
	 * @var $port
	 */
	private $port;

	/**
	 * @param $_hostname
	 * @param $_port
	 */
	function __construct($_hostname, $_port = null) {
		$this->hostname= $_hostname;
		$this->port = $_port;
	}

	/**
	 * get the hostname 
	 * @return $this->hostname
	 */
	public function getHostname() {
		return $this->hostname;
	}

	/**
	 * get the port
	 * @return $this->port
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * get the host like hostname:port
	 * @return string
	 */
	public function getHost() {
		if(isset($this->port)) return $this->hostname.':'.$this->port;
		else return $this->hostname;
	}
}
?>