<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Extensions
 * @copyright   Copyright (c) 2016 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

require_once(Mage::getBaseDir('app') . DS .'code'. DS .'core'. DS .'Mage'. DS .'Checkout'. DS .'controllers'. DS .'OnepageController.php');

class Sashas_Onestep_OnestepController extends Mage_Checkout_OnepageController {
	
	/**
	 * Validate ajax call
	 * @return Sashas_Onestep_OnestepController
	 */
	public function _ajaxValidation(){
		if (!Mage::app()->getRequest()->isAjax()){
			$refererUrl = $this->_getRefererUrl();
			$this->getResponse()->setRedirect($refererUrl);
			return $this;
		}
		$this->_expireAjax();
		$this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
		return $this;
	}
 
	/**
	 * Checkout Index action 
	 */
	public function indexAction() {
         
        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message') ?
                Mage::getStoreConfig('sales/minimum_order/error_message') :
                Mage::helper('checkout')->__('Subtotal must exceed minimum order amount');

            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure' => true)));
        $this->getOnepage()->initCheckout();
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
	}
	
	/**
	 * Customer Login 
	 */
	public function loginAjaxAction(){
		
 		$this->_ajaxValidation();
		$response=array();
		$response['status']=0;
		
		if (!$this->_validateFormKey()) {
			$this->_redirect('*/*/');
			return;
		}
  
		$session = Mage::getSingleton('customer/session');
		
		if ($this->getRequest()->isPost()) {
			$login = $this->getRequest()->getPost('login');
			if (!empty($login['username']) && !empty($login['password'])) {
				try {					 
					$session->login($login['username'], $login['password']);			
					$response['status']=1;
				} catch (Mage_Core_Exception $e) {
					switch ($e->getCode()) {
						case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
							$value = Mage::helper('customer')->getEmailConfirmationUrl($login['username']);
							$message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
							break;
						case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
							$message = $e->getMessage();
							break;
						default:
							$message = $e->getMessage();
					}
					$response['status']=0;
					$response['message']=$message;
					 
				} catch (Exception $e) {
					$response['status']=0;
					$response['message']=Mage::helper('customer')->__('Error during login attempt. Please try again');					 
				}
			} else {				 
				$response['status']=0;
				$response['message']=$this->__('Login and password are required.');
			}
		}
		
		$this->getResponse()->setBody(json_encode($response));
	}
	
	/**
	 * Get billing info action
	 */
	public function getBillingAction() 
	{
		$this->_ajaxValidation();		
		$response['content']=$this->_getBillingHtml();
		$this->getResponse()->setBody(json_encode($response));
	}
	
	/**
	 * Return block content from the 'checkout_onestep_billing'
	 * This is the content for billing section
	 *
	 * @return string
	 */
	protected function _getBillingHtml()
	{
		$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->load('checkout_onestep_billing');
		$layout->generateXml();
		$layout->generateBlocks();
		$output = $layout->getOutput();
		Mage::getSingleton('core/translate_inline')->processResponseBody($output);
		return $output;
	}
	
	/**
	 * Get payment info action
	 */
	public function getPaymentAction()
	{
		$this->_ajaxValidation();
		$response['content']=$this->_getPaymentHtml();
		$this->getResponse()->setBody(json_encode($response));
	}
	
	/**
	 * Return block content from the 'checkout_onestep_payment'
	 * This is the content for payment section
	 *
	 * @return string
	 */
	protected function _getPaymentHtml()
	{
		$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->load('checkout_onestep_payment');
		$layout->generateXml();
		$layout->generateBlocks();
		$output = $layout->getOutput();
		Mage::getSingleton('core/translate_inline')->processResponseBody($output);
		return $output;
	}
	
	/**
	 * Get review info action
	 */
	public function getReviewAction()
	{
		$this->_ajaxValidation();
		$response['content']=$this->_getReviewHtml();
		$this->getResponse()->setBody(json_encode($response));
	}
	
	/**
	 * Return block content from the 'checkout_onestep_review'
	 * This is the content for review section
	 *
	 * @return string
	 */
	protected function _getReviewHtml()
	{
		$layout = $this->getLayout();
		$update = $layout->getUpdate();
		$update->load('checkout_onestep_review');
		$layout->generateXml();
		$layout->generateBlocks();
		$output = $layout->getOutput();
		Mage::getSingleton('core/translate_inline')->processResponseBody($output);
		return $output;
	}
}