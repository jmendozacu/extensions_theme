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
		$invoice= new Varien_Object;
				 
		$invoiceData=array();
		
		$invoiceItems=new Varien_Data_Collection();		 
		
		if (($handle = $fileStream->streamOpen($file, "r")) !== FALSE) {
			while (($data = $fileStream->streamReadCsv()) !== FALSE) {
			    if ($row==0) {
			        $invoice_id=trim($data[0]);
			        $total_time=trim($data[1]);
			        $total_amount=str_replace('$','',trim($data[2]));
			        $subtotal_amount=str_replace('$','',trim($data[3]));
			        $hourlyRate="";
                    $totalData0=array();
                    if (!is_numeric($total_time) && isset($data[5])) {
                        $hourlyRate=str_replace('$','',trim($data[5]));
                        $totalData0=array(
                            'label'=>'Hourly Rate',
                            'amount'=>$hourlyRate,
                        );
                    }
			        $invoiceData['invoice_id']=$invoice_id;
			        
			        /*Totals*/
			        $totalItems=new Varien_Data_Collection();
			        if (is_numeric($total_time)) {
                        $totalData1=array(
                            'label'=>'Total Amount',
                            'amount'=>$total_time,
                        );
                    } else {
                        $totalData1=array(
                            'label'=>'Total Time',
                            'amount'=>$total_time,
                        );
                    }

			        $totalData2=array(
			                'label'=>'Subtotal',
			                'amount'=>$subtotal_amount,
			        );
			        $totalData3=array(
			                'label'=>'Total',
			                'amount'=>$total_amount,
			        );
			         if (count($totalData0)) {
                         $totalItems->addItem(new Varien_Object($totalData0));
                     }
			        $totalItems->addItem(new Varien_Object($totalData1));
			        $totalItems->addItem(new Varien_Object($totalData2));
			        $totalItems->addItem(new Varien_Object($totalData3));
			        
			        $invoiceData['totals']=$totalItems;			        
			        /*Totals*/
			        $row++;
			        continue;
			    }
			    
				$date = trim($data[0]);				 
				$time=trim($data[3]);
				$description=trim($data[4]);
 
				$itemData=array(
				        'date'=>strtotime($date),
				        'description'=>$description,
				        'value'=>$time,
				);
				$invoiceItems->addItem(new Varien_Object($itemData));
				$row++;
			}
			$invoiceData['items']=$invoiceItems;
			 
			$invoice->setData($invoiceData); 
		}
		fclose($handle);
		unlink($file);
		return $invoice;
	}
}