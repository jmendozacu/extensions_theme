<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Extensions
 * @copyright   Copyright (c) 2016 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Sashas_Onestep_Block_Billing extends Mage_Checkout_Block_Onepage_Billing {
	

	public function getAddressesHtmlSelect($type)
	{
		if ($this->isCustomerLoggedIn()) {
			$options = array();
			foreach ($this->getCustomer()->getAddresses() as $address) {
				$options[] = array(
						'value' => $address->getId(),
						'label' => $address->format('oneline')
				);
			}
	
			$addressId = $this->getAddress()->getCustomerAddressId();
			if (empty($addressId)) {
				if ($type=='billing') {
					$address = $this->getCustomer()->getPrimaryBillingAddress();
				} else {
					$address = $this->getCustomer()->getPrimaryShippingAddress();
				}
				if ($address) {
					$addressId = $address->getId();
				}
			}
	
			$select = $this->getLayout()->createBlock('core/html_select')
			->setName($type.'_address_id')
			->setId($type.'-address-select')
			->setClass('address-select')
			->setExtraParams('onchange="new'.ucfirst($type).'Address((!this.value));"')
			->setValue($addressId)
			->setOptions($options);
	
			$select->addOption('', Mage::helper('checkout')->__('New Address'));
	
			return $select->getHtml();
		}
		return '';
	}
	
}