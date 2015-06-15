<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Magapp
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_Magapp_Model_Order_Api extends Mage_Sales_Model_Order_Api
{
 
	
	/**
	 * Retrieve list of orders by filters
	 *
	 * @param array $filters
	 * @return array
	 */
	public function last($filters = null)
	{
				 
		$result = array();
		//TODO: add full name logic
		$billingAliasName = 'billing_o_a';
		$shippingAliasName = 'shipping_o_a';
	
		$collection = Mage::getModel("sales/order")->getCollection()
		->addAttributeToSelect('*')
		->addAddressFields()
		->addExpressionFieldToSelect(
				'billing_firstname', "{{billing_firstname}}", array('billing_firstname'=>"$billingAliasName.firstname")
		)
		->addExpressionFieldToSelect(
				'billing_lastname', "{{billing_lastname}}", array('billing_lastname'=>"$billingAliasName.lastname")
		)
		->addExpressionFieldToSelect(
				'shipping_firstname', "{{shipping_firstname}}", array('shipping_firstname'=>"$shippingAliasName.firstname")
		)
		->addExpressionFieldToSelect(
				'shipping_lastname', "{{shipping_lastname}}", array('shipping_lastname'=>"$shippingAliasName.lastname")
		)
		->addExpressionFieldToSelect(
				'billing_name',
				"CONCAT({{billing_firstname}}, ' ', {{billing_lastname}})",
				array('billing_firstname'=>"$billingAliasName.firstname", 'billing_lastname'=>"$billingAliasName.lastname")
		)
		->addExpressionFieldToSelect(
				'shipping_name',
				'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
				array('shipping_firstname'=>"$shippingAliasName.firstname", 'shipping_lastname'=>"$shippingAliasName.lastname")
		);
		
		 
		
		if (is_array($filters)) {
			try {
				foreach ($filters as $field => $value) {
					if ($field=='limit') 
						$collection->getSelect()->limit($value);
					
					if ($field=='day' && is_array($value)) {
						$from=Mage::getModel('core/date')->gmtTimestamp( strtotime($value['from'].' 00:00:00'));						 
						$fromDate = date('Y-m-d H:i:s', $from);
						$to=Mage::getModel('core/date')->gmtTimestamp( strtotime($value['to'].' 23:59:59'));
						$toDate = date('Y-m-d H:i:s', $to);
						 					 
						Mage::log('Order List Filter From: '.$fromDate.' To '.$toDate, null, 'magapp.log');
						
						$collection->addFieldToFilter('created_at', array('from'=>$fromDate));
						$collection->addFieldToFilter('created_at', array('to'=>$toDate));
					}elseif ($field=='day') {
						$from=Mage::getModel('core/date')->gmtTimestamp( strtotime($value.' 00:00:00'));						 
						$fromDate = date('Y-m-d H:i:s', $from);
						$to=Mage::getModel('core/date')->gmtTimestamp( strtotime($value.' 23:59:59'));
						$toDate = date('Y-m-d H:i:s', $to);
						
						Mage::log('Order List Filter for date: '.$fromDate, null, 'magapp.log');
						
						$collection->addFieldToFilter('created_at', array('from'=>$fromDate));
						$collection->addFieldToFilter('created_at', array('to'=>$toDate));
												
					}
				}								
			} catch (Mage_Core_Exception $e) {
				$this->_fault('filters_invalid', $e->getMessage());				
			}
			
			$collection->getSelect()->order('created_at DESC');
		} else {
			$collection->getSelect()->order('created_at DESC');
			Mage::log('Last 10 Orders', null, 'magapp.log');
			$collection->getSelect()->limit(10);
		}
  		
		
		
		foreach ($collection as $order) {
			$order_info=$this->_getAttributes($order, 'order');
			Mage::log('Order Status: '.$order_info['status'], null, 'magapp.log');
			$order_info['status']=Mage::getModel('sales/order_status')->getCollection()->addFieldToFilter('status',array('eq'=>$order_info['status']))->getFirstItem()->getLabel();
			$result[] = $order_info;
		}
		
		 
	
		return $result;
	}
	
	/**
	 * Retrieve full order information
	 *
	 * @param string $orderIncrementId
	 * @return array
	 */
	public function info($orderIncrementId)
	{
		$order= $this->_initOrder($orderIncrementId);
		
		/* @var $order Mage_Sales_Model_Order */
		
	
		if ($order->getGiftMessageId() > 0) {
			$order->setGiftMessage(
					Mage::getSingleton('giftmessage/message')->load($order->getGiftMessageId())->getMessage()
			);
		}
	
		$result = $this->_getAttributes($order, 'order');
		/*Sashas*/
		if ($_extOrderId = $order->getExtOrderId()) {
			$_extOrderId = '[' . $_extOrderId . '] ';
		} else {
			$_extOrderId = '';
		}
 
		$created_at=$this->formatDate($order->getCreatedAt(), 'medium', true);
		$result['order_title']=Mage::helper('sales')->__('Order # %s %s | %s', $order->getRealOrderId(), $_extOrderId, $created_at);
		$result['customer_group_title']=$this->getCustomerGroupName($order);
		 
		$result['can_cancel']=($order->canCancel() ? 1 : 0);
		$result['can_hold']=($order->canHold() ? 1 : 0);
		$result['can_unhold']=($order->canUnHold() ? 1 : 0);
		$result['can_ship']=($order->canShip() ? 1 : 0);
		$result['can_invoice']=($order->canInvoice() ? 1 : 0);
		$result['can_creditmemo']=($order->canCreditmemo() ? 1 : 0);
		$result['can_comment']=($order->canComment() ? 1 : 0);
		
		/*Sashas*/
		if ($result['is_virtual']==0) {
			$result['shipping_address'] = $this->_getAttributes($order->getShippingAddress(), 'order_address');
			$shipping_address=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $order->getShippingAddress()->format('text'));
			$result['shipping_address_html']=trim($shipping_address);
		}
	 
		$result['billing_address']  = $this->_getAttributes($order->getBillingAddress(), 'order_address');
		$billing_address=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $order->getBillingAddress()->format('text'));
		$result['billing_address_html']=trim($billing_address);
		$result['items'] = array();
	 
		foreach ($order->getAllItems() as $item) {		 
			if ($item->getGiftMessageId() > 0) {
				$item->setGiftMessage(
						Mage::getSingleton('giftmessage/message')->load($item->getGiftMessageId())->getMessage()
				);
			}
		     $item_data=array();
		     $item_data=$this->_getAttributes($item, 'order_item');
		     $item_data['sub_info']=""; 
		     $item_data['item_status']= $item->getStatus();
		    
		     if ($item_data['product_options']) {		     
			     $item_options=unserialize($item_data['product_options']);
			     $item_data['unserialized_options']=$item_options;
			     if (isset($item_data['unserialized_options']['simple_name']) )
			     	$item_data['simple_name']=$item_data['unserialized_options']['simple_name'];
			     
			     if (isset($item_data['unserialized_options']['simple_sku']) ) {
			     	$item_data['simple_sku']=$item_data['unserialized_options']['simple_sku'];		    
			     	$sku= $item_data['simple_sku'];
			     }elseif (isset($item_data['sku']) ) {
			     	$sku=$item_data['sku'];
			     }    
		     } 
		      
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
		}
		 
		$result['payment'] = $this->_getAttributes($order->getPayment(), 'order_payment');
		$paymentInfoBlock =Mage::helper('payment')->getInfoBlock($order->getPayment());
		$result['payment_method_text'] =str_replace("<br />","",preg_replace('#<br />(\s*<br />)+#', "",  nl2br(trim(strip_tags($paymentInfoBlock->toHtml())))));
		$result['payment_currency_text']=trim(Mage::helper('sales')->__('Order was placed using %s', $order->getOrderCurrencyCode()));
		  
	  	/*@TODO add Inc Excl Taxes*/
		 $totals = $this->PrepareTotals($order);		 
		foreach ($totals as $_total) {
			$total_container=array();
			$total_container['title']=$_total->getLabel() ;
			$total_container['value']=strip_tags($this->formatValue($_total,$order));
			$result['totals'][]=$total_container;
		}  
		 
		
		$result['status_history'] = array();
	
		foreach ($order->getAllStatusHistory() as $history) {
			$result['status_history'][] = $this->_getAttributes($history, 'order_status_history');
		}
		
		$result['status']=Mage::getModel('sales/order_status')->getCollection()->addFieldToFilter('status',array('eq'=>$result['status']))->getFirstItem()->getLabel();
		
		return $result;
	}
	
	public function getCustomerGroupName($order)
	{
		if ($order) {
			return Mage::getModel('customer/group')->load((int)$order->getCustomerGroupId())->getCode();
		}
		return null;
	}
	
	public function formatDate($date = null, $format =  Mage_Core_Model_Locale::FORMAT_TYPE_SHORT, $showTime = false)
	{
		return Mage::helper('core')->formatDate($date, $format, $showTime);
	}
	
	protected function PrepareTotals($order)
	{
		$totals= array();
		$totals[] = new Varien_Object(array(
				'code'      => 'subtotal',
				'value'     => $order->getSubtotal(),
				'base_value'=> $order->getBaseSubtotal(),
				'label'     =>  Mage::helper('sales')->__('Subtotal')
		));
	
		/**
		 * Add shipping
		 */
		if (!$order->getIsVirtual() && ((float) $order->getShippingAmount() || $order->getShippingDescription()))
		{
			$totals[] = new Varien_Object(array(
					'code'      => 'shipping',
					'value'     => $order->getShippingAmount(),
					'base_value'=> $order->getBaseShippingAmount(),
					'label' =>  Mage::helper('sales')->__('Shipping & Handling')
			));
		}
	
		/**
		 * Add discount
		 */
		if (((float)$order->getDiscountAmount()) != 0) {
			if ($order->getDiscountDescription()) {
				$discountLabel =  Mage::helper('sales')->__('Discount (%s)', $order->getDiscountDescription());
			} else {
				$discountLabel =  Mage::helper('sales')->__('Discount');
			}
			$totals[] = new Varien_Object(array(
					'code'      => 'discount',
					'value'     => $order->getDiscountAmount(),
					'base_value'=> $order->getBaseDiscountAmount(),
					'label'     => $discountLabel
			));
		}
		
		$totals[] = new Varien_Object(array(
				'code'      => 'tax',
				'value'     => $order->getTaxAmount(),
				'base_value'=> $order->getBaseTaxAmount(),
				'label'     => Mage::helper('sales')->__('Tax')
		));
		
		$totals[] = new Varien_Object(array(
				'code'      => 'grand_total',
				'strong'    => true,
				'value'     => $order->getGrandTotal(),
				'base_value'=> $order->getBaseGrandTotal(),
				'label'     =>  Mage::helper('sales')->__('Grand Total'),
				'area'      => 'footer'
		));
	
		$totals[] = new Varien_Object(array(
				'code'      => 'paid',
				'strong'    => true,
				'value'     => $order->getTotalPaid(),
				'base_value'=> $order->getBaseTotalPaid(),
				'label'     =>  Mage::helper('sales')->__('Total Paid'),
				'area'      => 'footer'
		));
		$totals[] = new Varien_Object(array(
				'code'      => 'refunded',
				'strong'    => true,
				'value'     => $order->getTotalRefunded(),
				'base_value'=> $order->getBaseTotalRefunded(),
				'label'     =>  Mage::helper('sales')->__('Total Refunded'),
				'area'      => 'footer'
		));
		$totals[] = new Varien_Object(array(
				'code'      => 'due',
				'strong'    => true,
				'value'     => $order->getTotalDue(),
				'base_value'=> $order->getBaseTotalDue(),
				'label'     =>  Mage::helper('sales')->__('Total Due'),
				'area'      => 'footer'
		));
		
		return $totals;
	}
	
	
	
	
	public function formatValue($total,$order)
	{
		if (!$total->getIsFormated()) {
			return  Mage::helper('adminhtml/sales')->displayPrices(
					$order,
					$total->getBaseValue(),
					$total->getValue()
			);
		}
		return $total->getValue();
	}
	
	
	/**  
	 * Notification check for new orders
	 * @return multitype:string multitype:mixed  multitype:unknown  
	 */
	public function notifications()
	{
		$orders = array();		 
		
		$model=Mage::getModel('magapp/notifications');
		foreach ($model->getCollection() as $new_order) {
			$order_info['order_id']=$new_order->getOrderId();
			$order_info['increment_id']=$new_order->getIncrementId();
			$order_info['amount']=Mage::helper('core')->currency($new_order->getGrandTotal(), true, false);
			$order_info['status']=ucfirst($new_order->getStatus());
			$orders[]=$order_info;
			$new_order->delete();
		}
		 		
		return $orders;
	
	}
	
 
	
	
}
