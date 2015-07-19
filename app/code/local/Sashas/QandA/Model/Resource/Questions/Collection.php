<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Model_Resource_Questions_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
		
	/**
	 * Define resource model
	 *
	 */
	protected function _construct()
	{
		$this->_init('qanda/questions');	
	}	
}
