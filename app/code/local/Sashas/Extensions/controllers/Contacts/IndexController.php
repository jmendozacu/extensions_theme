<?php
 
/**
 * Contacts index controller
 *
 * @category   Mage
 * @package    Mage_Contacts
 * @author      Magento Core Team <core@magentocommerce.com>
 */ 

require_once(Mage::getBaseDir('app') . DS .'code'. DS .'core'. DS .'Mage'. DS .'Contacts'. DS .'controllers'. DS .'IndexController.php');

class Sashas_Extensions_Contacts_IndexController extends Mage_Contacts_IndexController
{
    
    const XML_PATH_PUBLIC_KEY  = 'recaptcha/recaptcha_group/site_key';
    const XML_PATH_PRIVATE_KEY = 'recaptcha/recaptcha_group/secret_key';
     
    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['comment']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }
                /*reCaptcha*/
                if (!Zend_Validate::is(trim($post['g-recaptcha-response']), 'NotEmpty')) {
                    $error = true;                    
                }    
                            
                $secret=Mage::getStoreConfig('recaptcha/recaptcha_group/secret_key');
                $client = new Varien_Http_Client('https://www.google.com/recaptcha/api/siteverify');
                $client->setMethod(Varien_Http_Client::POST);
                $client->setParameterPost('secret', $secret);
                $client->setParameterPost('response', $post['g-recaptcha-response']);
               
                $response = $client->request();
                if ($response->isSuccessful()) {
                	$result=json_decode($response->getBody());
                	if (!isset($result->success) || $result->success!=1) 
                	    $error = true;
                } else {
                        $error = true;
                }              
                /*reCaptcha*/                 
                 
                if ($error) {
                    throw new Exception();
                }
                
                 
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($post['email'])
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please check captcha and try again'));
                $this->_redirect('*/*/');
                return;
            }

        } else {
            $this->_redirect('*/*/');
        }
    }
    
    public function postAjaxAction()
    {
        if (!Mage::app()->getRequest()->isAjax()){
            $this->_redirect('*/*/');
            return;
        }
        
        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
        
        
        $post = $this->getRequest()->getPost();
        $status='error';
        
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);
    
                $error = false;
    
                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }
    
                if (!Zend_Validate::is(trim($post['comment']) , 'NotEmpty')) {
                    $error = true;
                }
    
                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }
    
                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }
                /*reCaptcha*/
                if (!Zend_Validate::is(trim($post['g-recaptcha-response']), 'NotEmpty')) {
                    $error = true;
                }
    
                $secret=Mage::getStoreConfig('recaptcha/recaptcha_group/secret_key');
                $client = new Varien_Http_Client('https://www.google.com/recaptcha/api/siteverify');
                $client->setMethod(Varien_Http_Client::POST);
                $client->setParameterPost('secret', $secret);
                $client->setParameterPost('response', $post['g-recaptcha-response']);
                 
                $response = $client->request();
                if ($response->isSuccessful()) {
                    $result=json_decode($response->getBody());
                    if (!isset($result->success) || $result->success!=1)
                        $error = true;
                } else {
                    $error = true;
                }
                /*reCaptcha*/
                 
                if ($error) {
                    throw new Exception();
                }
    
                 
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                ->setReplyTo($post['email'])
                ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                );
    
                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }
    
                $translate->setTranslateInline(true);    
                $message=Mage::helper('contacts')->__('Your request has been submitted. Thank you');
				$status='success';                                
            } catch (Exception $e) {
                $translate->setTranslateInline(true);    
                $message=Mage::helper('contacts')->__('There was an issue during request. Make sure you checked captcha and try to submit it again, please.');                                
            }
    
        } else {
            $message=Mage::helper('contacts')->__('There was an issue during request. Make sure you have filled all fields.');
        }
        
        $this->getResponse()->setBody(json_encode(array('status'=>$status,'response'=>$message)));
         
    }    

}
