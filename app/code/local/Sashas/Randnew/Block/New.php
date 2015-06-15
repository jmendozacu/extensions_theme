<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Randnew
 * @copyright   Copyright (c) 2014 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Randnew_Block_New extends Mage_Catalog_Block_Product_New
{
	protected function _beforeToHtml()
	{		 
		parent::_beforeToHtml();
		$collection=$this->getProductCollection();
		$collection->getSelect()->order('rand()');
		$this->setProductCollection($collection);
		return $this;
	}
}