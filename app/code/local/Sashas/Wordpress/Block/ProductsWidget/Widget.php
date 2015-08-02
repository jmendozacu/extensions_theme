<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Wordpress
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */
class Sashas_Wordpress_Block_ProductsWidget_Widget extends Mage_Catalog_Block_Product_New {

    
    /* (non-PHPdoc)
     * @see Mage_Core_Block_Template::_construct()
    */
    protected function _construct()
    {
        $this->setTemplate('wordpress/new.phtml');
        
        $this->addData(array('cache_lifetime' => 3600));
        $this->addCacheTag(array(
                Mage_Core_Model_Store::CACHE_TAG
        ));
    
        parent::_construct();
    }
    
 
    
    
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $this->setColumnCount(6);
        $collection=$this->getProductCollection();
        $collection->setPageSize(6);
        $collection->setCurPage(1);
        $collection->getSelect()->order('rand()');
        $collection->getSelect()->limit(3);
        $this->setProductCollection($collection);
        return $this;
    }
    
    
}