<?php

/**
 * Abstract tag block for rich snippets blocks
 * @category    StrongTics
 * @package     StrongTics_RichSnippets
 * @author      Issa BERTHE <issa.berthe@strongtics.com>
 * @copyright   Copyright (c) StrongTics
 */
abstract class StrongTics_RichSnippets_Block_Abstract extends Mage_Core_Block_Template {

    abstract protected function _canShow();

    protected function _toHtml() {
        if (!$this->_canShow() || !$this->_getConfigHelper()->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * Get Google+ page link url
     * 
     * @return string 
     */
    public function getGooglePlusPageUrl() {
        return $this->_getConfigHelper()->getGooglePlusPageUrl();
    }

    /**
     * Get the config helper
     *
     * @return StrongTics_RichSnippets_Helper_Config
     */
    protected function _getConfigHelper() {
        return Mage::helper('sticsrichsnippets/config');
    }

}
