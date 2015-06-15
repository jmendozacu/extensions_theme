<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Widgetemailproduct
 * @copyright   Copyright (c) 2014 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Widgetemailproduct_Block_Product extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface {
 
	 
	 /* 
	  * @see Mage_Core_Block_Template::_toHtml()
	  */
	 protected function _toHtml()
	{
		$idPath = explode('/', $this->_getData('id_path'));
		if (isset($idPath[1])) {
			$id = $idPath[1];
			$product=Mage::getModel('catalog/product')->load($id);
		} else {
			return false;
		}	
		$td_with=$this->_getData('container_width');
		$image_w=$this->_getData('image_width');
		$image_h=$this->_getData('image_height');
		$show_price=$this->_getData('show_price');
		$show_name=$this->_getData('show_name');
		$show_cart=$this->_getData('show_cart');
		$add_to_cart_color=$this->_getData('button_color');
		$add_to_cart_text_color=$this->_getData('button_text_color');
		$add_to_cart_border_color=$this->_getData('button_border_color');
		$show_shortdescription=$this->_getData('show_shortdescription');
		$block=$this->getLayout()->createBlock('catalog/product_list')
				->setTemplate('widgetemailproduct/product.phtml')
				->setProduct($product)
				->setTdWith($td_with)
				->setImgW($image_w)
				->setImgH($image_h)
				->setShowPrice($show_price)	
				->setShowName($show_name)
				->setShowCart($show_cart)
				->setButtonColor($add_to_cart_color)
				->setButtonTextColor($add_to_cart_text_color)
				->setButtonBorderColor($add_to_cart_border_color)
				->setShowDescr($show_shortdescription);
				
		return $block->toHtml();
	} 
	
	
 
 
 
	
}
?>