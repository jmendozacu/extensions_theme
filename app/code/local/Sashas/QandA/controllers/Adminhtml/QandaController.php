<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Adminhtml_QandaController extends Mage_Adminhtml_Controller_Action {

    const XML_PATH_EMAIL_TEMPLATE   = 'qanda/qanda_group/answered_question_email_template';    
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    
    
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Sashas_QandA');
    }
    
    
    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/qanda');
    }
    
    /**
     * Questions admin grid page
     */
    public function indexAction()
    {   
        $this->_title($this->__('Catalog')) ->_title($this->__('Questions & Answers'));
        $this->loadLayout()->_setActiveMenu('catalog/qanda');
        $content_block=$this->getLayout()->createBlock('qanda/adminhtml_questions');
        $this->getLayout()->getBlock('content')->append($content_block);
        $this->renderLayout();      
    }
    
    
    /**
     * Edit Question
     */
    public function editAction()
    {
        $this->_title($this->__('Catalog'))->_title($this->__('Questions & Answers'));
    
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('qanda/questions');
             
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qanda')->__('This question no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
         
        Mage::register('qanda_question', $model);
     
        $this->loadLayout()->_setActiveMenu('catalog/qanda')->_addBreadcrumb(Mage::helper('qanda')->__('Answer Question'));
        
        $content_block=$this->getLayout()->createBlock('qanda/adminhtml_questions_edit');
        $this->getLayout()->getBlock('content')->append($content_block);
        $this->renderLayout();
    }
    
    public function massDeleteAction()
    {
        $questionsIds = $this->getRequest()->getParam('entity_id');
        if (!is_array($questionsIds)) {
            $this->_getSession()->addError($this->__('Please select question(s).'));
        } else {
            if (!empty($questionsIds)) {
                try {
                    foreach ($questionsIds as $questionId) {
                        $question = Mage::getModel('qanda/questions')->load($questionId);
                        $question->delete();
                    }
                    $this->_getSession()->addSuccess(
                            $this->__('Total of %d record(s) have been deleted.', count($questionsIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * Save action
     */
    public function saveAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {    
            $id = $this->getRequest()->getParam('entity_id');
            $model = Mage::getModel('qanda/questions')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qanda')->__('This question no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
 
            $answered=0;
            $_product=Mage::getModel('catalog/product')->load($model->getProductId());
            if (!$model->getAnswer() && isset($data['answer']) && strlen($data['answer'])>0) {
                $change_status=Mage::getStoreConfig('qanda/qanda_group/change_status_auto');
                if ($change_status)
                	$data['status']=1;                
                $answered=1;
            }
            
            $model->setData($data);
 
            try {
                
                $model->save();               
                
                /*Email*/
                $translate = Mage::getSingleton('core/translate');
                /* @var $translate Mage_Core_Model_Translate */
                $translate->setTranslateInline(false);
                $send_answer_email=Mage::getStoreConfig('qanda/qanda_group/send_email_answered');                 
                 
                if ($answered && $send_answer_email && isset($data['email'])) {
                    $recipient_email=$data['email'];
                    $postObject = new Varien_Object();
                    $data['product_name']=$_product->getName();
                    $data['product_url']=Mage::getUrl('catalog/product/view/',array('id'=>$_product->getId()));
                    $postObject->setData($data);
                    
                    $mailTemplate = Mage::getModel('core/email_template');
                    /* @var $mailTemplate Mage_Core_Model_Email_Template */
                    $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($params['email'])
                    ->sendTransactional(
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                            Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                            $recipient_email,
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
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qanda')->__('The question has been saved.'));              
                Mage::getSingleton('adminhtml/session')->setFormData(false);    
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getEntityId()));
                    return;
                }               
                $this->_redirect('*/*/');
                return;
    
            } catch (Exception $e) {                 
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());                
                Mage::getSingleton('adminhtml/session')->setFormData($data);               
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('entity_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
    
    /**
     * Delete action
     */
    public function deleteAction()
    {                
        if ($id = $this->getRequest()->getParam('id')) {           
            try {               
                $model = Mage::getModel('qanda/questions')->load($id);       
                $model->delete();                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qanda')->__('The question has been deleted.'));                
                $this->_redirect('*/*/');
                return;
    
            } catch (Exception $e) {   
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());                
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qanda')->__('Unable to find a question to delete.')); 
        $this->_redirect('*/*/');
    }
}