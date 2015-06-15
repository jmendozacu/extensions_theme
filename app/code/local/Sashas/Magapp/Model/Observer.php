<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Magapp
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license    http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Magapp_Model_Observer {
	static protected $_singletonFlag = false;
	
	public function notificationSave(Varien_Event_Observer $observer) {
		$order = $observer->getOrder();
		
		$model=Mage::getModel('magapp/notifications');
		$model->setOrderId($order->getEntityId());
		$model->setIncrementId($order->getIncrementId());
		$model->setGrandTotal($order->getGrandTotal());
		$model->setStatus($order->getStatus());
		$model->setStoreId($order->getStoreId());
		$model->save();
		
		
		return $this;
		
	}
	
}