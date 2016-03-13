<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Extensions
 * @copyright   Copyright (c) 2016 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Sashas_Onestep_Block_Login extends Mage_Checkout_Block_Onepage_Login {
	
	/**
	 * @return Mage_Customer_Model_Session
	 */
	public function isCustomerLoggedIn()
	{
		return Mage::getSingleton('customer/session')->isLoggedIn();
	}
	
	public function getPostAction()
	{
		return Mage::getUrl('checkout/onestep/loginAjax', array('_secure'=>true));
	}
	
}