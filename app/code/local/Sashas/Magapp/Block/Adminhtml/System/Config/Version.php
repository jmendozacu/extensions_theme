<?php
/**
* @category    Sashas
* @package     Sashas_Magapp
* @author      Sashas IT Support <support@sashas.org>
* @copyright   2007-2016 Sashas IT Support Inc. (http://www.sashas.org)
* @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
* @link        http://www.extensions.sashas.org/magento-android-manager.html
*/

class Sashas_Magapp_Block_Adminhtml_System_Config_Version extends Mage_Adminhtml_Block_System_Config_Form_Field
{
 
	 /**
	  * {@inheritDoc}
	  * @see Mage_Adminhtml_Block_System_Config_Form_Field::_getElementHtml()
	  */
	 protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {    	
    	$html=Mage::helper('magapp')->getVersion();    	
    	return $html;
    	 
    }
}
