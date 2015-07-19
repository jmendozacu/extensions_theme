<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Block_Adminhtml_Questions extends Mage_Adminhtml_Block_Widget_Grid_Container {
    
    public function __construct()
    {
        $this->_controller = 'adminhtml_questions';
        $this->_blockGroup = 'qanda';
        $this->_headerText = Mage::helper('qanda')->__('Product Questions');
        parent::__construct();
    
    }
        
}