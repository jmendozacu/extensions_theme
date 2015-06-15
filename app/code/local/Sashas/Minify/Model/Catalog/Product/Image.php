<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Minify
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_Minify_Model_Catalog_Product_Image extends Mage_Catalog_Model_Product_Image {

    protected $_quality = 50;
    
    /**
     * @return Varien_Image
     */
    public function getImageProcessor()
    {
        $isActive=Mage::getStoreConfig('minify/minify_group/optimize_images');
        if (!$isActive)
            parent::getImageProcessor();
        
        $quality=Mage::getStoreConfig('minify/minify_group/image_quality');
        
        if( !$this->_processor ) {
            //            var_dump($this->_checkMemory());
            //            if (!$this->_checkMemory()) {
            //                $this->_baseFile = null;
            //            }
            $this->_processor = new Varien_Image($this->getBaseFile());
        }
        $this->_processor->keepAspectRatio($this->_keepAspectRatio);
        $this->_processor->keepFrame($this->_keepFrame);
        $this->_processor->keepTransparency($this->_keepTransparency);
        $this->_processor->constrainOnly($this->_constrainOnly);
        $this->_processor->backgroundColor($this->_backgroundColor);
        $this->_processor->quality($quality);
        return $this->_processor;
    }    

}