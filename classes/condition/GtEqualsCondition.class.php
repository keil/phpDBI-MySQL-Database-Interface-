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
 * $Id: GtEqualsCondition.class.php 803 2010-05-20 13:47:08Z webadmin $
 * $HeadURL: http://svn.rm-keil.de/rm-keil/webpages/rm-keil.de/Release%20(1.0)/httpdocs/_app/core/database/condition/GtEqualsCondition.class.php $
 * $Date: 2010-05-20 15:47:08 +0200 (Do, 20 Mai 2010) $
 * $Author: webadmin $
 * $Revision: 803 $
 **************************************************/

Application::import('core.database.condition.Condition');

/**
 * GtEqualsCondition
 */
class GtEqualsCondition extends Condition {

	/**
	 * @param $_key
	 * @param $_value
	 */
	function __construct($_key, $_value) {
		parent::__construct($_key, '>=', $_value);
	}
}
?>