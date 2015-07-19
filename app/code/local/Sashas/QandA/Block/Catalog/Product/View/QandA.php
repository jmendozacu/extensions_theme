<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Block_Catalog_Product_View_QandA extends Mage_Core_Block_Template {
 
    const STATUS_PENDING        = 0;
    const STATUS_APPROVED       = 1;  
    const STATUS_NOT_APPROVED   = 2;
    
    /* (non-PHPdoc)
     * @see Mage_Core_Block_Template::_construct()
     */
    protected function _construct()
    {
        $this->addData(
        	array(
                'cache_key' => $this->getCacheKeyInfo(),  
                'cache_lifetime'    => 3600,
                'cache_tags'        => array(Mage_Core_Model_Store::CACHE_TAG),
        	)
        );
       
        parent::_construct();
    }
    
    /* (non-PHPdoc)
     * @see Mage_Core_Block_Template::getCacheKeyInfo()
     */
    public function getCacheKeyInfo()
    {
        return array(
                'sashas_qanda_view',
                Mage::app()->getStore()->getId(),
                (int)Mage::app()->getStore()->isCurrentlySecure(),
                Mage::getDesign()->getPackageName(),
                Mage::getDesign()->getTheme('template')
        );
    }
    
    public function getQuestions(){
        $current_product = Mage::registry('current_product');
        $collection=Mage::getModel('qanda/questions')->getCollection()
        	->addFieldToFilter('store_id',array('eq'=>Mage::app()->getStore()->getId()))
        	->addFieldToFilter('product_id',array('eq'=>$current_product->getId()))
        	->addFieldToFilter('status',array('eq'=>self::STATUS_APPROVED))
        	->setOrder('created_at','DESC');
       
        return $collection;
    }
    
    public function getAllowNewQuestion(){
        return Mage::getStoreConfig('qanda/qanda_group/allow_ask');
    }
    
    public function getAction(){
        return Mage::getUrl('qanda/question/add');
    }
    
    public function isEmailRequired(){         
        return Mage::getStoreConfig('qanda/qanda_group/is_email_required');
    }
}