<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Smsnotification
 * @copyright   Copyright (c) 2013 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)

 */

class Sashas_Smsnotification_Model_Observer {
	
	static protected $_singletonFlag = false;
	const XML_PATH_UPDATE_EMAIL_TEMPLATE        = 'sales_email/order_comment/template';
	const XML_PATH_UPDATE_EMAIL_IDENTITY        = 'sales_email/order_comment/identity';
	
	public function Sendnotification(Varien_Event_Observer $observer) {
		
		if (!self::$_singletonFlag) 
			self::$_singletonFlag = true;
		
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		$order_id=$observer->getEvent()->getOrderIds();		 	
		$order = Mage::getModel('sales/order')->load($order_id);
		$store_name=  Mage::app()->getStore()->getName();		 	
		$storeId = Mage::app()->getStore()->getStoreId();
		$carrier=Mage::getStoreConfig('smsnotification/smsnotification_group/carrier_setup',$storeId);
		$phone=Mage::getStoreConfig('smsnotification/smsnotification_group/phone_setup',$storeId);
		$templateId = Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_TEMPLATE, $storeId);
		try {
			if (!$carrier ||  !$phone)
				throw new Exception('Incorrect number or Carrier');
			
			if ($carrier==1) 
				$email="@txt.att.net";
			elseif ($carrier==2)
				$email="@tmomail.net";
			elseif ($carrier==3)			
				$email="@messaging.sprintpcs.com";
			elseif ($carrier==4)	
				$email="@vtext.com";
			 
			 /*
			$mailer = Mage::getModel('core/email_template_mailer');
			$emailInfo = Mage::getModel('core/email_info');
			$emailInfo->addTo($phone.$email, $store_name);
			$mailer->setSender(Mage::getStoreConfig(self::XML_PATH_UPDATE_EMAIL_IDENTITY, $storeId));
			$mailer->setStoreId($storeId);
			$mailer->setTemplateId($templateId);
			$mailer->setTemplateParams(array(
					'order'   => $order,
					'comment' => "New order was placed.",
					'billing' => $order->getBillingAddress()			
			)
			);
			$mailer->send();*/
			
			$postObject = new Varien_Object();
			$postObject->setName("Sashas Extensions Order Notify");
			$postObject->setComment("New order was placed. $".$order->getBaseGrandTotal().". Status: ".$order->getStatus());		 
			
			$mailTemplate = Mage::getModel('core/email_template');
			/* @var $mailTemplate Mage_Core_Model_Email_Template */
			
			include Mage::getBaseDir() . '/app/code/core/Mage/Contacts/controllers/IndexController.php';
			$postObject->setEmail(Mage::getStoreConfig(Mage_Contacts_IndexController::XML_PATH_EMAIL_SENDER));
			$mailTemplate->setDesignConfig(array('area' => 'frontend'))
			->setReplyTo($postObject->getEmail())
			->sendTransactional(
					Mage::getStoreConfig(Mage_Contacts_IndexController::XML_PATH_EMAIL_TEMPLATE),
					Mage::getStoreConfig(Mage_Contacts_IndexController::XML_PATH_EMAIL_SENDER),
					$phone.$email,
					null,
					array('data' => $postObject)
			);
			
 		} catch (Exception $e) {
 				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
 		}		
		 return $this;
	}
	

}