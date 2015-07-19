<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Model_Resource_Questions extends Mage_Core_Model_Resource_Db_Abstract
{

	/**
	 * Initialize resource model
	 *
	 */
	protected function _construct()
	{
		$this->_init('qanda/questions', 'entity_id');
	}
	
	/**
	 * Perform actions before object save
	 *
	 * @param Varien_Object $object
	 * @return Mage_Qanda_Model_Resource_Questions
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
	    if (!$object->getId()) {
	        $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
	    }
	    
	    return $this;
	}
		
}
