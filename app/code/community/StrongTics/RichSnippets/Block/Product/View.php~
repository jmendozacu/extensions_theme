<?php

/**
 * Product view rich snippets scope
 * @category    StrongTics
 * @package     StrongTics_RichSnippets
 * @author      Issa BERTHE <issa.berthe@strongtics.com>
 * @copyright   Copyright (c) StrongTics
 */
class StrongTics_RichSnippets_Block_Product_View extends Mage_Catalog_Block_Product {

    protected function _construct() {
        parent::_construct();

        $this->setCacheLifetime(3600);
    }

    public function getCacheKeyInfo() {
        $product = $this->getProduct();

        return array_merge(
                        parent::getCacheKeyInfo(), array(
                    'product_id' => ($product ? $product->getId() : 0),
                    'currency' => Mage::app()->getStore()->getCurrentCurrencyCode()
                ));
    }

    public function getCacheTags() {
        $product = $this->getProduct();

        return array_merge(
                        parent::getCacheTags(), array(Mage_Catalog_Model_Product::CACHE_TAG
                    . ($product ? '_' . $product->getId() : '')));
    }

    protected function _toHtml() {
        if (!$this->_getConfigHelper()->isEnabled() || !$this->_getConfigHelper()->getProductActivation()) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * Get the current product
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct() {
        return Mage::registry('current_product');
    }

    public function getEscapedProductName() {
        return $this->escapeHtml($this->getProduct()->getName());
    }

    public function getImageUrl() {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        $imageHelper = Mage::helper('catalog/image');

        return $imageHelper->init($product, 'image');
    }

    public function getEscapedDescription() {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        return $this->escapeHtml($product->getDescription());
    }
   
    public function getEscapedManufacturerName() {
        return $this->escapeHtml($this->getProduct()->getAttributeText('manufacturer'));
    }	
	
    public function getEscapedCategoryName() {
        $category = $this->getProduct()->getCategory();
        if (!$category) {
            return '';
        }

        return $this->escapeHtml($category->getName());
    }

    public function getSku() {
        $product = $this->getProduct();
        if (!$product) {
            return '';
        }

        return $product->getSku();
    }

	
    public function getCurrentCurrency() {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    public function getCurrencySymbol() {
        return Mage::app()->getLocale()->currency($this->getCurrency())->getSymbol();
    }

    protected function _getReviewSummaryCollection() {
        return Mage::getModel('review/review_summary')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($this->getProduct()->getId());
    }

    public function getReviewsCount() {
        return $this->_getReviewSummaryCollection()->getReviewsCount();
    }

    public function getRatingSummary() {
        return $this->_getReviewSummaryCollection()->getRatingSummary();
    }

    public function getRating() {
        return $this->_getReviewSummaryCollection()->getRatingSummary();
    }

    protected function _getReviewCollection() {
        return Mage::getModel('review/review')
                        ->getResourceCollection()
                        ->addStoreFilter(Mage::app()->getStore()->getId())
                        ->addEntityFilter('product', $this->getProduct()->getId())
                        ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                        ->setDateOrder()
                        ->addRateVotes()
                        ->load();
    }

    public function getFirstReview() {
        return $this->_getReviewCollection()->getFirstItem();
    }

    public function getCleanDate($date) {
        return date('Y-m-d', strtotime($date));
    }

    public function getBestRating() {
        $bestRating = 100;
        return $bestRating;
    }

    public function getWorstRating() {
        $worstRating = 0;
        return $worstRating;
    }
	
    /**
     * Returns product final price formatted
     * @return string 
     */
    public function getProductFinalPrice() {
        return number_format($this->getProduct()->getFinalPrice(), 2);
    }
	
    /**
     * Check if product is available to be sale
     * @return bool 
     */
    public function getProductAvailability() {
        return $this->getProduct()->isSalable();
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
