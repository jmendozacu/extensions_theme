<?php
class Sashas_Magapp_Model_Dashboard_Api extends Mage_Api_Model_Resource_Abstract
{
 
	/**
	 * Retrieve charts
	 *
	 * @return array
	 */
	public function charts()
	{
				 
		$result = array();
	 
		$timezoneLocal = Mage::app()->getStore()->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);
		 
	/*	if ($filters['type']=='amounts'){
			$axisMaps=  array(
					'x' => 'range',
					'y' => 'revenue'
			);
			$dataRows=array('revenue');			
		} else {			
			$axisMaps=  array(
					'x' => 'range',
					'y' => 'quantity'
			);			
			$dataRows=array('quantity');
			 
		}*/
		 
		$datarow_orders=array('quantity');
		$axisMapsOrders=  array('x' => 'range','y' => 'quantity');
		
		$datarow_amounts=array('revenue');
		$axisMapsAmounts=  array('x' => 'range','y' => 'revenue');
		
		$helperName='adminhtml/dashboard_order';
		$helper= Mage::helper($helperName); 		 
		//???
		$availablePeriods = array_keys(Mage::helper('adminhtml/dashboard_data')->getDatePeriods());
		 
		$i=0;
		foreach ($availablePeriods as $period) {
 
			$axiLabelsAmounts=array();
			$axiLabelsOrders=array();
			$allSeriesAmounts=array();
			$allSeriesOrders=array();
			
			$allSeriesAmounts= $this->getRowsData($datarow_amounts,false,$period);
			$allSeriesOrders= $this->getRowsData($datarow_orders,false,$period);
			 
			foreach ($axisMapsAmounts as $axis => $attr){
				$axiLabelsAmounts[$axis] =  $this->getRowsData($attr,true,$period);
			}
			
			foreach ($axisMapsOrders as $axis => $attr){
				$axiLabelsOrders[$axis] =  $this->getRowsData($attr,true,$period);
			}			
 		 
			list ($dateStart, $dateEnd) = Mage::getResourceModel('reports/order_collection')->getDateRange($period, '', '', true);
			
			$dateStart->setTimezone($timezoneLocal);
			$dateEnd->setTimezone($timezoneLocal);
			
			$dates = array();
			$datasAmounts = array();
			$datasOrders = array();
			 
			while($dateStart->compare($dateEnd) < 0){
				switch ($period) {
					case '24h':
						$d = $dateStart->toString('yyyy-MM-dd HH:00');
						$dateStart->addHour(1);
						break;
					case '7d':
					case '1m':
						$d = $dateStart->toString('yyyy-MM-dd');
						$dateStart->addDay(1);
						break;
					case '1y':
					case '2y':
						$d = $dateStart->toString('yyyy-MM');
						$dateStart->addMonth(1);
						break;
				}
				foreach ($allSeriesAmounts as $index=>$serie) {
					if (in_array($d, $axiLabelsAmounts['x'])  ) {						 				
						$datasAmounts[] = (float)array_shift($allSeriesAmounts[$index]);							 
					} else {
						$datasAmounts[] = 0;						 
					}				 					
				}	 
				
				foreach ($allSeriesOrders as $index=>$serie) {
					if (in_array($d, $axiLabelsOrders['x']) ) {
						$datasOrders[] = (float)array_shift($allSeriesOrders[$index]);
					} else {
						$datasOrders[] = 0;
					}
				}				
			 				
				$dates[] = strtotime($d);
			}
			 
			if(!count($datasAmounts)) {
				$datasAmounts=array_fill(0,count($dates),0);
			}
			if(!count($datasOrders)) {
				$datasOrders=array_fill(0,count($dates),0);
			}			
			
			$resultAmounts[$i]['x']=$dates;
			$resultAmounts[$i]['y']=$datasAmounts;
			$resultAmounts[$i]['totals']=$this->getTotals($period);
			$resultAmounts[$i]['period']=$period; 
			 
			$resultOrders[$i]['x']=$dates;
			$resultOrders[$i]['y']=$datasOrders;
			$resultOrders[$i]['totals']=$this->getTotals($period);
			$resultOrders[$i]['period']=$period;			
			
			$i++;
		}
	 
	 
	 
		return array('amounts'=>$resultAmounts,'orders'=>$resultOrders);
	}
	 
	/**
	 * Get rows data
	 *
	 * @param array $attributes
	 * @param bool $single
	 * @return array
	 */
	protected function getRowsData($attributes, $single = false, $period)
	{
		$collection = Mage::getResourceModel('reports/order_collection')->prepareSummary($period, 0, 0, 0);
		$items=$collection->getItems();
		$options = array();
		foreach ($items as $item){
			if ($single) {
				$options[] = max(0, $item->getData($attributes));
			} else {
				foreach ((array)$attributes as $attr){
					$options[$attr][] = max(0, $item->getData($attr));
				}
			}
		}
		return $options;
	}
	
	protected function getTotals($period) {
		$price_default=Mage::helper('core')->currency(0, true, false);
		$total=array($price_default,$price_default,$price_default,0);
		
		$collection = Mage::getResourceModel('reports/order_collection')
		->addCreateAtPeriodFilter($period)
		->calculateTotals(0);
		
		$collection->load();
		
		$totals = $collection->getFirstItem();
		
		if ($totals->getRevenue())
			$total[0]=Mage::helper('core')->currency($totals->getRevenue(), true, false);
		if ($totals->getTax())
			$total[1]=Mage::helper('core')->currency($totals->getTax(), true, false);
		if ($totals->getShipping())
			$total[2]=Mage::helper('core')->currency($totals->getShipping(), true, false);
		if ($totals->getQuantity())
			$total[3]=$totals->getQuantity()*1;
	 
		return $total;
	}
	 
 
 
	 
}
