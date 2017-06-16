<?php
/**
 * @category    Sashas
 * @package     Sashas_Magapp
 * @author      Sashas IT Support <support@sashas.org>
 * @copyright   2007-2016 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 * @link        http://www.extensions.sashas.org/magento-android-manager.html
 */
class Sashas_Magapp_Block_Adminhtml_System_Config_Status extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    /**
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $apiUsername=Mage::getStoreConfig('magapp/magapp_group/api_username');
        $apiKey=Mage::getStoreConfig('magapp/magapp_group/api_key');
        $url=Mage::getBaseUrl();
        
        if (!$apiUsername) {
            $status="Api username is empty or not saved";
            $state="critical";
        } elseif (!$apiKey) {
            $status="Api key is empty or not saved";
            $state="critical";
        } else {
            $apiResponse=Mage::helper('magapp')->testRequest($url,$apiUsername,$apiKey);
            $state="minor";
            if ($apiResponse===0) {
            	$status='Unknown Error';
            } elseif($apiResponse==1){
            	$status='Internal Error. Please see log for details';
            } elseif($apiResponse==2){
            	$status='Access denied.';
            } elseif($apiResponse==3){
            	$status='IInvalid api url.';
            } elseif($apiResponse==4){
            	$status='Resource path is not callable.';
            } else {            	           
            	$status='Valid';
            	$state="notice";
            }                       
        }
        
        return '<span class="grid-severity-'.$state.'"><span style=" background-color: #FAFAFA;">'.$status.
            '</span></span>';
    }
}
