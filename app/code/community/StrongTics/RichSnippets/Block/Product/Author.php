<?php

/**
 * Author rich snippets link for product view head
 * @category    StrongTics
 * @package     StrongTics_RichSnippets
 * @author      Issa BERTHE <issa.berthe@strongtics.com>
 * @copyright   Copyright (c) StrongTics
 */
class StrongTics_RichSnippets_Block_Product_Author extends Maxiscoot_RichSnippets_Block_Abstract {

    function __construct() {
        parent::__construct();
        $this->setCacheLifetime(3600);
    }

    protected function _canShow() {
        if (!$this->_getConfigHelper()->getAuthorActivation()) {
            return false;
        }

        return true;
    }

    protected function _prepareLayout() {
        $head = $this->getLayout()->getBlock('head');

        if (!$head || !$this->getGooglePlusPageUrl()) {
            return parent::_prepareLayout();
        }

        $head->addLinkRel('author', $this->getGooglePlusPageUrl());

        return parent::_prepareLayout();
    }

}
