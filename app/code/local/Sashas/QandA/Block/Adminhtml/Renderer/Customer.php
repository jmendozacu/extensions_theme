<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Block_Adminhtml_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
    
    public function render(Varien_Object $row)
    {
        $customer_id =  $row->getData('customer_id');
        $value=parent::render($row);
        if ($customer_id)
        	$value='<a href="'.Mage::helper("adminhtml")->getUrl('adminhtml/customer/edit',array('id'=>$customer_id)).'" target="_blank"  >'.$value.'</a>';
    
        return $value;
        	
    }
}