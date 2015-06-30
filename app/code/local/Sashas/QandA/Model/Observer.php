<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Model_Observer {
    static protected $_singletonFlag = false;
    
    public function addProductTabBlock(Varien_Event_Observer $observer) {
    
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)){
            	
            $block->addTab('qanda', array(
                    'label'     => Mage::helper('catalog')->__('Product Questions'),
                    'content'   => $block->getLayout()->createBlock('qanda/adminhtml_catalog_product_tab_questions')->toHtml(),
            ));
    
        }
        
        $customer = Mage::registry('current_customer');
         
        if ($block instanceof Mage_Adminhtml_Block_Customer_Edit_Tabs && $customer->getId()){
           
            $block->addTabAfter('qanda', array(
                    'label'     => Mage::helper('customer')->__('Product Questions'),
                    'content'   => $block->getLayout()->createBlock('qanda/adminhtml_customer_account_tab_questions')->toHtml(),
            ), 'tags');
        
        }
        
        return $this;
    }
    
    protected function _canAddTab($product){
        if ($product->getId()){
            return true;
        }
        if (!$product->getAttributeSetId()){
            return false;
        }
        $request = Mage::app()->getRequest();
        /* @todo Disable for configurable products or simple */
        if ($request->getParam('type') == 'configurable'){
            if ($request->getParam('attributes')){
                return true;
            }
        }
        return false;
    }
}