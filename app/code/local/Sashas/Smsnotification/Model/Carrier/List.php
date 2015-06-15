<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Smsnotification
 * @copyright   Copyright (c) 2013 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)

 */

class Sashas_Smsnotification_Model_Carrier_List
{

	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
				array('value' => 4, 'label'=>Mage::helper('adminhtml')->__('Verizon')),
				array('value' => 3, 'label'=>Mage::helper('adminhtml')->__('Sprint')),
				array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('T-mobile')),
				array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('AT&T')),
				array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('Please Select...')),
		);
	}

	/**
	 * Get options in "key-value" format
	 *
	 * @return array
	 */
	public function toArray()
	{
		return array(
				0 => Mage::helper('adminhtml')->__('Please Select...'),
				1 => Mage::helper('adminhtml')->__('AT&T'),
				2 => Mage::helper('adminhtml')->__('T-mobile'),
				3 => Mage::helper('adminhtml')->__('Sprint'),
				4 => Mage::helper('adminhtml')->__('Verizon'),
		);
	}

}