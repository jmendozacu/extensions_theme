<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Magapp
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_Magapp_Model_Creditmemo_Api extends Mage_Sales_Model_Order_Creditmemo_Api
{

	
	/**
	 * Retrieve credit memo information
	 *
	 * @param string $creditmemoIncrementId
	 * @return array
	 */
	public function info($creditmemoIncrementId)
	{
		$creditmemo = $this->_getCreditmemo($creditmemoIncrementId);
		// get credit memo attributes with entity_id' => 'creditmemo_id' mapping
		$result = $this->_getAttributes($creditmemo, 'creditmemo');
		$result['order_increment_id'] = $creditmemo->getOrder()->load($creditmemo->getOrderId())->getIncrementId();
		 
		$result['can_cancel'] = ($creditmemo->canCancel() ? 1 : 0);
		 
		// items refunded
		$result['items'] = array();
		foreach ($creditmemo->getAllItems() as $item) {
			/*Sashas*/
			$item_data= $this->_getAttributes($item, 'creditmemo_item');
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
			$result['items'][] = $item_data;
			/*Sashas*/
		}
		 
		/*Sashas*/
		$totals = $this->PrepareTotals($creditmemo);
		foreach ($totals as $_total) {
			$total_container=array();
			$total_container['title']=$_total->getLabel() ;
			$total_container['value']=strip_tags($this->formatValue($_total,$creditmemo));
			$result['totals'][]=$total_container;
		}
		/*Sashas*/
		
		// credit memo comments
		$result['comments'] = array();
		foreach ($creditmemo->getCommentsCollection() as $comment) {
			$result['comments'][] = $this->_getAttributes($comment, 'creditmemo_comment');
		}
		return $result;
	}
		 
	
	
	protected function PrepareTotals($creditmemo)
	{
		$totals= array();
		$totals[] = new Varien_Object(array(
				'code'      => 'subtotal',
				'value'     => $creditmemo->getSubtotal(),
				'base_value'=> $creditmemo->getBaseSubtotal(),
				'label'     =>  Mage::helper('sales')->__('Subtotal')
		));
	
		/**
		 * Add shipping
		 */
		if (!$creditmemo->getIsVirtual() && ((float) $creditmemo->getShippingAmount() || $creditmemo->getShippingDescription()))
		{
			$totals[] = new Varien_Object(array(
					'code'      => 'shipping',
					'value'     => $creditmemo->getShippingAmount(),
					'base_value'=> $creditmemo->getBaseShippingAmount(),
					'label' =>  Mage::helper('sales')->__('Shipping & Handling')
			));
		}
	
		/**
		 * Add discount
		 */
		if (((float)$creditmemo->getDiscountAmount()) != 0) {
			if ($creditmemo->getDiscountDescription()) {
				$discountLabel =  Mage::helper('sales')->__('Discount (%s)', $creditmemo->getDiscountDescription());
			} else {
				$discountLabel =  Mage::helper('sales')->__('Discount');
			}
			$totals[] = new Varien_Object(array(
					'code'      => 'discount',
					'value'     => $creditmemo->getDiscountAmount(),
					'base_value'=> $creditmemo->getBaseDiscountAmount(),
					'label'     => $discountLabel
			));
		}
		 
		$totals[] = new Varien_Object(array(
				'code'      => 'adjustment_positive',
				'value'     => $creditmemo->getAdjustmentPositive(),
				'base_value'=> $creditmemo->getBaseAdjustmentPositive(),
				'label'     => Mage::helper('sales')->__('Adjustment Refund')
		));
		 
		$totals[] = new Varien_Object(array(
				'code'      => 'adjustment_negative',
				'value'     => $creditmemo->getAdjustmentNegative(),
				'base_value'=> $creditmemo->getBaseAdjustmentNegative(),
				'label'     => Mage::helper('sales')->__('Adjustment Fee')
		));		
		
		$totals[] = new Varien_Object(array(
				'code'      => 'tax',
				'value'     => $creditmemo->getTaxAmount(),
				'base_value'=> $creditmemo->getBaseTaxAmount(),
				'label'     => Mage::helper('sales')->__('Tax')
		));
		
		$totals[] = new Varien_Object(array(
				'code'      => 'grand_total',
				'strong'    => true,
				'value'     => $creditmemo->getGrandTotal(),
				'base_value'=> $creditmemo->getBaseGrandTotal(),
				'label'     =>  Mage::helper('sales')->__('Grand Total'),				
		));		
	
		
		return $totals;
	}
	
	
	
	
	public function formatValue($total,$creditmemo)
	{
		if (!$total->getIsFormated()) {
			return  Mage::helper('adminhtml/sales')->displayPrices(
					$creditmemo,
					$total->getBaseValue(),
					$total->getValue()
			);
		}
		return $total->getValue();
	}
		
	
}
 
