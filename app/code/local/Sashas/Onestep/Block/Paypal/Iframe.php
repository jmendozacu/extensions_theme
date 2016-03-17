<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Extensions
 * @copyright   Copyright (c) 2016 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */


class Sashas_Onestep_Block_Paypal_Iframe extends Mage_Paypal_Block_Iframe
{
    
    protected function _construct()
    {
        parent::_construct();
        $paymentCode = $this->_getCheckout()
            ->getQuote()
            ->getPayment()
            ->getMethod();
        if (in_array($paymentCode, $this->helper('paypal/hss')->getHssMethods())) {
            $this->_paymentMethodCode = $paymentCode;
            $templatePath = str_replace('_', '', $paymentCode);
            $templateFile = "onestep/paypal/{$templatePath}/iframe.phtml";
            if (file_exists(Mage::getDesign()->getTemplateFilename($templateFile))) {
                $this->setTemplate($templateFile);
            } else {
                $this->setTemplate('onestep/paypal/hss/iframe.phtml');
            }
            
        }
        Mage::log($this->getTemplate(), null, 'paypal_template.log');
    }

    /**
     * Render the block if needed
     *
     * @return string
     */
    protected function _toHtml()
    {
    	Mage::log('after_save: '.$this->_isAfterPaymentSave(), null, 'paypal_template.log');
    	Mage::log($this->getTemplate(), null, 'paypal_template.log');
        if ($this->_isAfterPaymentSave()) {
            $this->setTemplate('onestep/paypal/hss/js.phtml');
            return parent::_toHtml();
        }
        if (!$this->_shouldRender) {
            return '';
        }
        return parent::_toHtml();
    }

   
}
