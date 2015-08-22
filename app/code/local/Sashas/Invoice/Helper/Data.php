<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Invoice
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)

 */

class Sashas_Invoice_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	/**
	 * @param (string) $file
	 */
	public function processfile($file){
		$row = 0;
		$fileStream = new Varien_Io_File();
		
		if (($handle = $fileStream->streamOpen($file, "r")) !== FALSE) {
			while (($data = $fileStream->streamReadCsv()) !== FALSE) {
				if ($row==0) {
					$row++;
					continue;
				}
				 
				$sku = trim($data[0]);
				 
				$invoice_enabled=trim($data[1]);
				$invoice_addtocart_enabled=trim($data[2]);
				$invoice_text=trim($data[3]);
				$excluded_customer_groups=trim($data[4]);
				$show_price=trim($data[5]);
				$store_id=trim($data[6]);
				
				if (!$invoice_enabled)
					$invoice_enabled=0;
				if (!$invoice_addtocart_enabled)
					$invoice_addtocart_enabled=0;
				if (!$store_id)
					$store_id=0;
				
				$_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
				
				if (!$_product || !$_product->getId())  {
					throw new Exception("Product ".$sku." doesn't found. Please check if this sku correct or product exists.");					
				}
				
				$product_id= $_product->getId();
				$model=Mage::getModel('invoice/invoice')->loadByProductId($product_id);
				 
				if (!$model->getId()) {
					$model->setProductId($product_id);
				}
				
				$model->setAddtocartEnabled($invoice_addtocart_enabled);
				$model->setStatus($invoice_enabled);				 
				$model->setValue($invoice_text);
				$model->setShowPrice($show_price);
				$model->setStoreId($store_id);
				$model->setCustomerGroups($excluded_customer_groups);
				$model->save();
				
			}
		}
		fclose($handle);
		unlink($file);
	}
}