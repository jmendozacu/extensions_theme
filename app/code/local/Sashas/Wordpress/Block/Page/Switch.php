<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Wordpress
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
class Sashas_Wordpress_Block_Page_Switch extends Mage_Page_Block_Switch {
    
    /**
     * 
     */
    protected function _construct()
    {        
        parent::_construct();
        $this->setTemplate('wordpress/page/switch/languages.phtml');       
    }
    /**
     * @param Mage_Core_Url_Store $store
     */
    public function getStoreUrl(Mage_Core_Model_Store $store ) {
       
        $store_id = Mage::app()->getStore()->getId();
        $wp_url=Mage::getStoreConfig('wordpress/wordpress_group/wp_folder', $store_id);
        
        $sidQueryParam = Mage::getModel('core/session')->getSessionIdQueryParam();
        $requestString = Mage::getSingleton('core/url')->escape(
                ltrim(Mage::app()->getRequest()->getRequestString(), '/'));
        
        $storeUrl = Mage::app()->getStore()->isCurrentlySecure()
        ? $store->getUrl('', array('_secure' => true))
        : $store->getUrl('');
        $storeParsedUrl = parse_url($storeUrl);
        
        $storeParsedQuery = array();
        if (isset($storeParsedUrl['query'])) {
            parse_str($storeParsedUrl['query'], $storeParsedQuery);
        }
        
        $currQuery = Mage::app()->getRequest()->getQuery();
        if (isset($currQuery[$sidQueryParam]) && !empty($currQuery[$sidQueryParam])
                &&  Mage::getModel('core/session')->getSessionIdForHost($storeUrl) != $currQuery[$sidQueryParam]
        ) {
            unset($currQuery[$sidQueryParam]);
        }
        
        foreach ($currQuery as $k => $v) {
            $storeParsedQuery[$k] = $v;
        }
        
        if (!Mage::getStoreConfigFlag(Mage_Core_Model_Store::XML_PATH_STORE_IN_URL, $store->getCode())) {
            $storeParsedQuery['___store'] = $store->getCode();
        }        
        
        $storeParsedQuery['___from_store'] =  Mage::app()->getStore()->getCode();
        
        $url=$storeParsedUrl['scheme'] . '://' . $storeParsedUrl['host']
            . (isset($storeParsedUrl['port']) ? ':' . $storeParsedUrl['port'] : '')
            . $storeParsedUrl['path'] .$wp_url. $requestString
            . ($storeParsedQuery ? '?'.http_build_query($storeParsedQuery, '', '&amp;') : '');
               
        return $url;
        
    }
}