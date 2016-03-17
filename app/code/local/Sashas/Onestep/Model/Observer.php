<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Extensions
 * @copyright   Copyright (c) 2016 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Sashas_Onestep_Model_Observer {
	/**
	 * Set data for response of frontend saveOrder action
	 *
	 * @param Varien_Event_Observer $observer
	 * @return Mage_Paypal_Model_Observer
	 */
	public function setResponseAfterSaveOrder(Varien_Event_Observer $observer)
	{
		/* @var $order Mage_Sales_Model_Order */
		$order = Mage::registry('hss_order');
	 
		if ($order && $order->getId()) {
			$payment = $order->getPayment();
			if ($payment && in_array($payment->getMethod(), Mage::helper('paypal/hss')->getHssMethods())) {
				/* @var $controller Mage_Core_Controller_Varien_Action */
				$controller = $observer->getEvent()->getData('controller_action');
				$result = Mage::helper('core')->jsonDecode(
						$controller->getResponse()->getBody('default'),
						Zend_Json::TYPE_ARRAY
						);
	
				if (empty($result['error'])) {
					$controller->loadLayout('checkout_onestep_paypal');
					$html = $controller->getLayout()->getBlock('paypal.iframe')->toHtml();
					$result['update_section'] = array(
							'name' => 'paypaliframe',
							'html' => $html
					);
					$result['redirect'] = false;
					$result['success'] = false;
					$controller->getResponse()->clearHeader('Location');
					$controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
				}
			}
		}
	
		return $this;
	}
}