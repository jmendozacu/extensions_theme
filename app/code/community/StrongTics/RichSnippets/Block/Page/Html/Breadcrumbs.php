<?php

/**
 * Maxiscoot_RichSnippets_Block_Page_Html_Breadcrumbs
 * @category    StrongTics
 * @package     StrongTics_RichSnippets
 * @author      Issa BERTHE <issa.berthe@strongtics.com>
 * @copyright   Copyright (c) StrongTics
 */
class StrongTics_RichSnippets_Block_Page_Html_Breadcrumbs extends Mage_Page_Block_Html_Breadcrumbs {

   /* protected function _construct() {
        parent::_construct();
  
        $this->setTemplate('sticsrichsnippets/page/html/breadcrumbs.phtml');		  
    } */
	
	protected function _toHtml() {
	
        if (!$this->_getConfigHelper()->isBreadcrumbsEnabled() || !$this->_getConfigHelper()->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * Get the config helper
     *
     * @return Maxiscoot_RichSnippets_Helper_Config
     */
    protected function _getConfigHelper() {
        return Mage::helper('sticsrichsnippets/config');
    }

}
