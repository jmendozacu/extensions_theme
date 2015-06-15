<?php

/**
 * Data helper
 * @category    StrongTics
 * @package     StrongTics_RichSnippets
 * @author      Issa BERTHE <issa.berthe@strongtics.com>
 * @copyright   Copyright (c) StrongTics 
 */
class StrongTics_RichSnippets_Helper_Config extends Mage_Core_Helper_Abstract {

    const XML_CONFIG_PATH = 'sticsrichsnippets/';

    protected $_mappingAddressCache = array();

    /**
     * Get enabled the rich snippets module
     *
     * @param Mage_Core_Model_Store|int $store
     * @return boolean
     */
    public function isEnabled($store = null) {
        return Mage::getStoreConfigFlag(self::XML_CONFIG_PATH . 'activation/enabled', $store);
    }

    /**
     * Get Google+ page url
     *
     * @param Mage_Core_Model_Store|int $store
     * @return string
     */
    public function getGooglePlusPageUrl($store = null) {
        return trim(Mage::getStoreConfig(self::XML_CONFIG_PATH . 'activation/google_plusone_author_page', $store));
    }

    /**
     * Get enabled the rich snippets module for breadcrumbs
     *
     * @param Mage_Core_Model_Store|int $store
     * @return boolean
     */
    public function isBreadcrumbsEnabled($store = null) {
        return $this->isEnabled($store)
                && Mage::getStoreConfigFlag(self::XML_CONFIG_PATH . 'breadcrumbs/enabled', $store); 
    }

    /**
     * Get enabled the Author rich snippets
     *
     * @param Mage_Core_Model_Store|int $store
     * @return boolean
     */
    public function getAuthorActivation($store = null) {
        return Mage::getStoreConfigFlag(self::XML_CONFIG_PATH . 'author/enabled', $store);
    }

    /**
     * Get enabled the Publisher rich snippets
     *
     * @param Mage_Core_Model_Store|int $store
     * @return boolean
     */
    public function getPublisherActivation($store = null) {
        return Mage::getStoreConfigFlag(self::XML_CONFIG_PATH . 'publisher/enabled', $store);
    }

    /**
     * Get enabled the organization markup for logo
     *
     * @param Mage_Core_Model_Store|int $store
     * @return  boolean
     */
    public function getLogoOrganizationActivation($store = null) {
        return Mage::getStoreConfigFlag(self::XML_CONFIG_PATH . 'organization/enabled', $store);
    }

    /**
     * Returns the store street address
     * @param Mage_Core_Model_Store|int $store
     * @return string 
     */
    public function getStoreStreetAddress($store = null) {
        return trim(Mage::getStoreConfig(self::XML_CONFIG_PATH . 'organization/store_street_address', $store));
    }

    /**
     * Returns the store postal code address
     * @param Mage_Core_Model_Store|int $store
     * @return string 
     */
    public function getStorePostalCodeAddress($store = null) {
        return trim(Mage::getStoreConfig(self::XML_CONFIG_PATH . 'author/store_postal_code_address', $store));
    }

    /**
     * Returns the store city address
     * @param Mage_Core_Model_Store|int $store
     * @return string 
     */
    public function getStoreCityAddress($store = null) {
        return trim(Mage::getStoreConfig(self::XML_CONFIG_PATH . 'organization/store_city_address', $store));
    }

    /**
     * Get store name
     * 
     * @return string 
     */
    public function getStoreName($store = null) {
        return trim(Mage::getStoreConfig('general/store_information/name', $store));
    }

    /**
     * Get enabled the rich snippets module for Product
     *
     * @param Mage_Core_Model_Store|int $store
     * @return boolean
     */
    public function getProductActivation($store = null) {
        return Mage::getStoreConfigFlag(self::XML_CONFIG_PATH . 'product/enabled', $store);
    }

    /**
     * Get store phone number
     *
     * @param Mage_Core_Model_Store|int $store
     * @returns string 
     */
    public function getStorePhoneNumber($store = null) {
        return trim(Mage::getStoreConfig('general/store_information/phone', $store));
    }

    /**
     * Get the merchant country information
     * @param Mage_Core_Model_Store|int $store
     * @return string 
     */
    public function getMerchantCountryInformation($store = null) {
        return Mage::getStoreConfig('general/store_information/merchant_country', $store);
    }

    /**
     * Get default setting store email
     * 
     * @param Mage_Core_Model_Store|int $store
     * @return string 
     */
    public function getStoreEmail($store = null) {
        return trim(Mage::getStoreConfig('contacts/email/recipient_email', $store));
    }

}
