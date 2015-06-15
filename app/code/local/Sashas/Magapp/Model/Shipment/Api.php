<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Magapp
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_Magapp_Model_Shipment_Api extends Mage_Sales_Model_Order_Shipment_Api
{

	
    /**
     * Retrieve shipment information
     *
     * @param string $shipmentIncrementId
     * @return array
     */
	
	public function info($shipmentIncrementId)
	{
		$shipment = Mage::getModel('sales/order_shipment')->loadByIncrementId($shipmentIncrementId);
	
		/* @var $shipment Mage_Sales_Model_Order_Shipment */
	
		if (!$shipment->getId()) {
			$this->_fault('not_exists');
		}
	
		$result = $this->_getAttributes($shipment, 'shipment');
		$result['order_increment_id'] = $shipment->getOrderIncrementId();
		
		$result['items'] = array();
		foreach ($shipment->getAllItems() as $item) {
			/*Sashas*/
			$item_data = $this->_getAttributes($item, 'shipment_item');
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
			 	
		$result['tracks'] = array();
		foreach ($shipment->getAllTracks() as $track) {
			$result['tracks'][] = $this->_getAttributes($track, 'shipment_track');
		}
	
		$result['comments'] = array();
		foreach ($shipment->getCommentsCollection() as $comment) {
			$result['comments'][] = $this->_getAttributes($comment, 'shipment_comment');
		}
	
		return $result;
	}
		

	/**
	 * Add tracking number to order
	 *
	 * @param string $shipmentIncrementId
	 * @param string $carrier
	 * @param string $title
	 * @param string $trackNumber
	 * @return int
	 */
	public function addTrack($shipmentIncrementId, $carrier, $title, $trackNumber, $sendEmail=false)
	{
		$shipment = Mage::getModel('sales/order_shipment')->loadByIncrementId($shipmentIncrementId);
	
		/* @var $shipment Mage_Sales_Model_Order_Shipment */
	
		if (!$shipment->getId()) {
			$this->_fault('not_exists');
		}
	
		$carriers = $this->_getCarriers($shipment);
	
		if (!isset($carriers[$carrier])) {
			$this->_fault('data_invalid', Mage::helper('sales')->__('Invalid carrier specified.'));
		}
	
		$track = Mage::getModel('sales/order_shipment_track')
		->setNumber($trackNumber)
		->setCarrierCode($carrier)
		->setTitle($title);
	
		$shipment->addTrack($track);
	
		try {
			$shipment->save();
			$track->save();
			if ($sendEmail){
				$shipment->setEmailSent($sendEmail);
				$shipment->sendEmail($sendEmail);
			}
		} catch (Mage_Core_Exception $e) {
			$this->_fault('data_invalid', $e->getMessage());
		}
	
		return $track->getId();
	}
			
	
}
 
