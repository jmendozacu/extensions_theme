<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Block_Customer_MyQuestions extends Mage_Customer_Block_Account_Dashboard {
    
    protected $_collection;
    
    const STATUS_PENDING        = 0;
    const STATUS_APPROVED       = 1;
    const STATUS_NOT_APPROVED   = 2;
        
    protected function _construct()
    {        
        $this->_collection =Mage::getModel('qanda/questions')->getCollection();
        $this->_collection->addFieldToFilter('store_id',array('eq'=>Mage::app()->getStore()->getId()))        
      /*  ->addFieldToFilter('status',array('eq'=>self::STATUS_APPROVED))*/
        ->addFieldToFilter('customer_id',array('eq'=>Mage::getSingleton('customer/session')->getCustomerId()))
        ->setOrder('created_at','ASC');
    }    
 
    public function count()
    {
        return $this->_collection->getSize();
    }
        
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }
    
    protected function _prepareLayout()
    {
        $toolbar = $this->getLayout()->createBlock('page/html_pager', 'customer_questions.toolbar')
        ->setCollection($this->_getCollection());
    
        $this->setChild('toolbar', $toolbar);
        return parent::_prepareLayout();
    }
    
    protected function _getCollection()
    {
        return $this->_collection;
    }
    
    public function getCollection()
    {
        return $this->_getCollection();
    } 
    
    public function getProductLink()
    {
        return Mage::getUrl('catalog/product/view/');
    }
    
    public function dateFormat($date)
    {
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }
    
    protected function _beforeToHtml()
    {
        $this->_getCollection()->load();
        return parent::_beforeToHtml();
    }    
}
