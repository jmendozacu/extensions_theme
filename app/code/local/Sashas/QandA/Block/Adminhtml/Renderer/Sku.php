<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */


class Sashas_QandA_Block_Adminhtml_Renderer_Sku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
	    $value=parent::render($row);
	    
		$product_id =  $row->getData('product_id');
		$value='<a href="'.Mage::helper("adminhtml")->getUrl('adminhtml/catalog_product/edit',array('id'=>$product_id)).'" target="_blank"  >'.$value.'</a>';
		 		
 		return $value;
		 
	}
}