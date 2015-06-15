<?php

/**
 * Organization Logo Rich Snippets
 * @category    StrongTics
 * @package     StrongTics_RichSnippets
 * @author      Issa BERTHE <issa.berthe@strongtics.com>
 * @copyright   Copyright (c) StrongTics
 */
class StrongTics_RichSnippets_Block_Page_Logo extends StrongTics_RichSnippets_Block_Abstract {

    function __construct() {
        parent::__construct();
        $this->setCacheLifetime(3600);
    }

    protected function _canShow() {
        if (!$this->_getConfigHelper()->getLogoOrganizationActivation()) {
            return false;
        }

        return true;
    }

    /**
     * Returns Store Url
     * 
     * @return string
     */
    public function getStoreUrl() {
        return Mage::getBaseUrl();
    }

    /**
     * Returns Store phone number
     * 
     * @returns string 
     */
    public function getStorePhoneNumber() {
        return $this->_getConfigHelper()->getStorePhoneNumber();
    }

    /**
     * Returns Store name
     * 
     * @return string
     */
    public function getStoreName() {
        return $this->_getConfigHelper()->getStoreName();
    }

    /**
     * Returns Organizarion logo source
     * 
     * @return string 
     */
    public function getOrganizationLogoSrc() {
        return $this->getSkinUrl(Mage::getStoreConfig('design/header/logo_src'));
    }

    /**
     * Returns store Email
     * 
     * @return string 
     */
    public function getStoreEmail() {
        return $this->_getConfigHelper()->getStoreEmail();
    }

    /**
     * Get store street address
     * 
     * @return string
     */
    public function getStoreStreetAddress() {
        return $this->_getConfigHelper()->getStoreStreetAddress();
    }

    /**
     * Get store postal code address
     * 
     * @return string
     */
    public function getStorePostalCodeAddress() {
        return $this->_getConfigHelper()->getStorePostalCodeAddress();
    }

    /**
     * Get store locality address
     * 
     * @return string
     */
    public function getStoreLocalityAddress() {
	    $country = $this->_getConfigHelper()->getMerchantCountryInformation();
	
     	if($country){			
			return $this->_getConfigHelper()->getStoreCityAddress() .', ' . Mage::app()->getLocale()->getCountryTranslation($country) ;
		}
		
		return $this->_getConfigHelper()->getStoreCityAddress();
    }

}
