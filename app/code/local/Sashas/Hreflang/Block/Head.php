<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Hreflang
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Hreflang_Block_Head extends Mage_Page_Block_Html_Head
{
 
	public function getCssJsHtml()
	{
 		  
 		$stores = Mage::app()->getStores();
 		foreach ($stores as $store)
 		{
 			$storeCode = substr(Mage::getStoreConfig('general/locale/code', $store->getId()),0,2); 		 
 			$url= $store->getCurrentUrl();
 			$url = strtok($url, '?');
 			$this->addLinkRel('alternate"' . ' hreflang="' . $storeCode, $url);
 		} 		
 		 		 
		return  parent::getCssJsHtml(); 	 
	}

	
}