<?php
/**
* @category    Sashas
* @package     Sashas_Magapp
* @author      Sashas IT Support <support@sashas.org>
* @copyright   2007-2016 Sashas IT Support Inc. (http://www.sashas.org)
* @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
* @link        http://www.extensions.sashas.org/magento-android-manager.html
*/

class Sashas_Magapp_Helper_Data extends Mage_Core_Helper_Abstract {
	
 
	/**
	 * @return string
	 */
	public function getVersion() {
		$version="";
		if ((string)Mage::getConfig()->getNode()->modules->Sashas_Magapp->version) 
			$version = (string) Mage::getConfig()->getNode()->modules->Sashas_Magapp->version;
		return $version;
	}
	
	/**
	 * @return mixed
	 */
	public function getApiUrl()
	{
		$returnUrl=Mage::helper("adminhtml")->getUrl("adminhtml/instagram/auth");
		$secureKey=Mage::getSingleton('adminhtml/url')->getSecretKey('instagram', 'auth');
		$keyString=Mage_Adminhtml_Model_Url::SECRET_KEY_PARAM_NAME.'/'.$secureKey.'/';
		$replaceString='?magento=1';
		$returnUrl=str_replace($keyString, $replaceString, $returnUrl);
		return $returnUrl;
	}
	
	public function testRequest($url, $apiUser, $apiKey){
		 try {
		 	$client = new Zend_XmlRpc_Client($url.'api/xmlrpc/');
		 	$session = $client->call('login', array($apiUser, $apiKey));
		 } catch (Exception $e) {
		 	return $e->getCode();
		 }
		  
		// If you don't need the session anymore
		$client->call('endSession', array($session));
		return $session;
	}
}