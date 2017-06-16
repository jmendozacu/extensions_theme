<?php
/**
* @category    Sashas
* @package     Sashas_Magapp
* @author      Sashas IT Support <support@sashas.org>
* @copyright   2007-2016 Sashas IT Support Inc. (http://www.sashas.org)
* @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
* @link        http://www.extensions.sashas.org/magento-android-manager.html
*/

class Sashas_Magapp_Model_Api extends Mage_Sales_Model_Order_Api
{
  
	/**
	 * @return string[]
	 */
	public function version()
	{		 
		return array('version'=>Mage::helper('magapp')->getVersion());	
	}
	
 
	
	
}
