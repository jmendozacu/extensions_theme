<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_QandA_QuestionController extends Mage_Core_Controller_Front_Action {
    
    const XML_PATH_EMAIL_TEMPLATE   = 'qanda/qanda_group/new_question_email_template';
    const XML_PATH_EMAIL_RECIPIENT  = 'qanda/qanda_group/asked_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    
    public function addAction(){
        if (!Mage::app()->getRequest()->isAjax()){
            $refererUrl = $this->_getRefererUrl();
            $this->getResponse()->setRedirect($refererUrl);
            return $this;
        }
        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
        
        $params=Mage::app()->getRequest()->getParams();        
        $params['store_id']=Mage::app()->getStore()->getId();
        $params['status']=0;
        
        $translate = Mage::getSingleton('core/translate');
        /* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);
        
        try {
            $question=Mage::getModel('qanda/questions');
            $question->setData($params)->save();
            
            /*Email*/           
            $send_email=Mage::getStoreConfig('qanda/qanda_group/send_email_asked');
            $recipient_email=Mage::getStoreConfig('qanda/qanda_group/asked_email');
             
            if ($send_email && $recipient_email && isset($params['email'])) {
                $postObject = new Varien_Object();
                $params['product_url']=Mage::getUrl('catalog/product/view/',array('id'=>$params['product_id']));
                $postObject->setData($params);
                
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                ->setReplyTo($params['email'])
                ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                );
                
                if (!$mailTemplate->getSentSuccess()) {
		            $this->getResponse()->setBody(json_encode('error_send_email'));
		            return;
                }
                
                $translate->setTranslateInline(true);
                
            }
            /*Email*/
            
            $response='success';
        } catch (Exception $e) {            
            $this->getResponse()->setBody(json_encode($e->getMessage()));
            return;
        }        
        $this->getResponse()->setBody(json_encode($response));
    }
    
    public function my_questionsAction(){ 
        
        if (Mage::getSingleton('customer/session')->isLoggedIn()){            
	        $this->loadLayout();
	        $this->_initLayoutMessages('customer/session');
	        $this->_initLayoutMessages('catalog/session');
	        
	        if ($block = $this->getLayout()->getBlock('customer_questions')) {
	            $block->setRefererUrl($this->_getRefererUrl());
	        }	        
	        $this->getLayout()->getBlock('head')->setTitle($this->__('My Product Questions'));	        	        	         	       
	        $this->renderLayout();	        
        } else {
            $this->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
        }
    }
    
    
}