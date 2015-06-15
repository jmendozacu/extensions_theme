<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Magapp
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_Magapp_Model_Invoice_Api extends Mage_Sales_Model_Order_Invoice_Api
{

	
	/**
	 * Retrieve invoice information
	 *
	 * @param string $invoiceIncrementId
	 * @return array
	 */
	public function info($invoiceIncrementId)
	{
		$invoice = Mage::getModel('sales/order_invoice')->loadByIncrementId($invoiceIncrementId);
	
		/* @var Mage_Sales_Model_Order_Invoice $invoice */
	
		if (!$invoice->getId()) {
			$this->_fault('not_exists');
		}
	
		$result = $this->_getAttributes($invoice, 'invoice');
		$result['order_increment_id'] = $invoice->getOrderIncrementId();
	
		$result['can_cancel'] = ($invoice->canCancel() ? 1 : 0);
		$result['can_capture'] = ($invoice->canCapture() ? 1 :0);		
			
		/*Sashas*/
		$result['items'] = array();
		foreach ($invoice->getAllItems() as $item) {
			 
			$item_data=  $this->_getAttributes($item, 'invoice_item');
			$item_data['sub_info']="";
			$item_data['item_status']= $item->getStatus();
			
			$item_data['qty_ordered']=$item->getQty();
			if (!$item_data['discount_amount'])
				$item_data['discount_amount']=0;
			
			$sku=$item->getSku();
			if ($sku)
				$_product_id = Mage::getModel('catalog/product')->getIdBySku('sku',$sku);
			if (!$_product_id)
				$_product_id=$item_data['product_id'];
			
			$_product = Mage::getModel('catalog/product')->load($_product_id);
			 
			$item_data['image']= (string) Mage::helper('catalog/image')->init($_product, 'image')->resize(240,240);
			 
			if (isset($item_data['unserialized_options']['attributes_info'])){
				foreach ($item_data['unserialized_options']['attributes_info'] as $opt) {
					$item_data['selected_options'][]=$opt['label'].": ".$opt['value'];
				}
			}
			 
			$result['items'][] =$item_data;			
			/*Sashas*/
		}
		
		$totals = $this->PrepareTotals($invoice);
		foreach ($totals as $_total) {
			$total_container=array();
			$total_container['title']=$_total->getLabel() ;
			$total_container['value']=strip_tags($this->formatValue($_total,$invoice));
			$result['totals'][]=$total_container;
		}		
	
		$result['comments'] = array();
		foreach ($invoice->getCommentsCollection() as $comment) {
			$result['comments'][] = $this->_getAttributes($comment, 'invoice_comment');
		}
	
		return $result;
	}
	
	
	protected function PrepareTotals($invoice)
	{
		$totals= array();
		$totals[] = new Varien_Object(array(
				'code'      => 'subtotal',
				'value'     => $invoice->getSubtotal(),
				'base_value'=> $invoice->getBaseSubtotal(),
				'label'     =>  Mage::helper('sales')->__('Subtotal')
		));
	
		/**
		 * Add shipping
		 */
		if (!$invoice->getIsVirtual() && ((float) $invoice->getShippingAmount() || $invoice->getShippingDescription()))
		{
			$totals[] = new Varien_Object(array(
					'code'      => 'shipping',
					'value'     => $invoice->getShippingAmount(),
					'base_value'=> $invoice->getBaseShippingAmount(),
					'label' =>  Mage::helper('sales')->__('Shipping & Handling')
			));
		}
	
		/**
		 * Add discount
		 */
		if (((float)$invoice->getDiscountAmount()) != 0) {
			if ($invoice->getDiscountDescription()) {
				$discountLabel =  Mage::helper('sales')->__('Discount (%s)', $invoice->getDiscountDescription());
			} else {
				$discountLabel =  Mage::helper('sales')->__('Discount');
			}
			$totals[] = new Varien_Object(array(
					'code'      => 'discount',
					'value'     => $invoice->getDiscountAmount(),
					'base_value'=> $invoice->getBaseDiscountAmount(),
					'label'     => $discountLabel
			));
		}
	
		$totals[] = new Varien_Object(array(
				'code'      => 'tax',
				'value'     => $invoice->getTaxAmount(),
				'base_value'=> $invoice->getBaseTaxAmount(),
				'label'     => Mage::helper('sales')->__('Tax')
		));
	
		$totals[] = new Varien_Object(array(
				'code'      => 'grand_total',
				'strong'    => true,
				'value'     => $invoice->getGrandTotal(),
				'base_value'=> $invoice->getBaseGrandTotal(),
				'label'     =>  Mage::helper('sales')->__('Grand Total'),
				'area'      => 'footer'
		));
	
		$totals[] = new Varien_Object(array(
				'code'      => 'paid',
				'strong'    => true,
				'value'     => $invoice->getTotalPaid(),
				'base_value'=> $invoice->getBaseTotalPaid(),
				'label'     =>  Mage::helper('sales')->__('Total Paid'),
				'area'      => 'footer'
		));
		$totals[] = new Varien_Object(array(
				'code'      => 'refunded',
				'strong'    => true,
				'value'     => $invoice->getTotalRefunded(),
				'base_value'=> $invoice->getBaseTotalRefunded(),
				'label'     =>  Mage::helper('sales')->__('Total Refunded'),
				'area'      => 'footer'
		));
 
	
		return $totals;
	}
	
	
	
	
	public function formatValue($total,$invoice)
	{
		if (!$total->getIsFormated()) {
			return  Mage::helper('adminhtml/sales')->displayPrices(
					$invoice,
					$total->getBaseValue(),
					$total->getValue()
			);
		}
		return $total->getValue();
	}
		
	
}
 
