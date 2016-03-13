<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Extensions
 * @copyright   Copyright (c) 2016 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
class Sashas_Onestep_Block_Index extends Mage_Checkout_Block_Onepage_Abstract {
	
	/**
	 * Get url for Billing section update
	 */
	public function getBillingAction() {
		return Mage::getUrl ( 'checkout/onestep/getBilling', array (
				'_secure' => true 
		) );
	}
	
	/**
	 * Get url for payment section update
	 */
	public function getPaymentAction() {
		return Mage::getUrl ( 'checkout/onestep/getPayment', array (
				'_secure' => true 
		) );
	}
	
	/**
	 * Get url for review section update
	 */
	public function getReviewAction() {
		return Mage::getUrl ( 'checkout/onestep/getReview', array (
				'_secure' => true 
		) );
	}
}