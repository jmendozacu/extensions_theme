<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Magapp
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license    http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Magapp_Model_Resource_Notifications extends Mage_Core_Model_Resource_Db_Abstract
{

	/**
	 * Initialize resource model
	 *
	 */
	protected function _construct()
	{
		$this->_init('magapp/notifications', 'notification_id');
	}
	
		
}
