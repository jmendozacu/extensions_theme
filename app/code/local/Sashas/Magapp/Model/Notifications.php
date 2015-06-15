<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Magapp
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license    http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Magapp_Model_Notifications extends Mage_Core_Model_Abstract
{
	const CACHE_TAG     = 'magapp_notifications';
	protected $_cacheTag= 'magapp_notifications';

	protected function _construct()
	{
		$this->_init('magapp/notifications');
	}
 
}
