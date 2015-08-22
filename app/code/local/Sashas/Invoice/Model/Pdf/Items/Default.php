<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Invoice
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_Invoice_Model_Pdf_Items_Default extends Mage_Sales_Model_Order_Pdf_Items_Abstract
{
    /**
     * Draw item line
     *
     */
    public function draw()
    {        
        $invoice  = $this->getInvoice();
        $item   = $this->getItem();
        $pdf    = $this->getPdf();
        $page   = $this->getPage();
        $lines  = array();
        $this->_setFontRegular();
        $lines[0] = array(array(
            'text' => Mage::helper('core/string')->str_split(date( 'n/d/Y', strtotime($item->getDate())), 20),
            'feed' => 35,
            'align' => 'left',
            'font'=>'regular',
            'font_size'=>8,
        ));

        $lines[0][] = array(
            'text'  => Mage::helper('core/string')->str_split($item->getDescription(), 118,true, true),
            'feed'  => 85,
            'font'=>'regular',
             'font_size'=>8,
        );
       	if (strpos($item->getValue(), ":")===false )
        	$value=Mage::helper('core')->currency($item->getValue(), true, false);
       	else 
       	    $value=Mage::helper('core/string')->str_split($item->getValue(),10);
       	
		$lines[0][] = array(
			'text'  => $value,
			'feed'  => 542,	
		    'font'=>'regular',
		    'font_size'=>8,		        
		);                   
               
        $lineBlock = array(
            'lines'  => $lines,
            'height' => 10,             
        );
        
        $page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $this->setPage($page);
    }
    
    
 
}
