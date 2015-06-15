<?php

/**
 * Publisher Rich Snippets
 * @category    StrongTics
 * @package     StrongTics_RichSnippets
 * @author      Issa BERTHE <issa.berthe@strongtics.com>
 * @copyright   Copyright (c) StrongTics
 */
class StrongTics_RichSnippets_Block_Page_Publisher extends StrongTics_RichSnippets_Block_Abstract {

    function __construct() {
        parent::__construct();
        $this->setCacheLifetime(3600);
    }

    protected function _prepareLayout() {
        $head = $this->getLayout()->getBlock('head');

        if (!$head || !$this->getGooglePlusPageUrl()) {
            return parent::_prepareLayout();
        }

        $head->addLinkRel('publisher', $this->getGooglePlusPageUrl());

        return parent::_prepareLayout();
    }

    protected function _canShow() {
        if (!$this->_getConfigHelper()->getPublisherActivation()) {
            return false;
        }

        if (!$this->_isHomePage()) {
            return false;
        }

        return true;
    }

    protected function _isHomePage() {
        return Mage::getBlockSingleton('page/html_header')->getIsHomePage();
    }

}
