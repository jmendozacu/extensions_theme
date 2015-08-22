<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Invoice
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0 GNU License, version 3 (GPL-3.0)
 */

class Sashas_Invoice_Model_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Abstract{
       
    /**
     * Insert logo to pdf page
     *
     * @param Zend_Pdf_Page $page
     * @param null $store
     */
    protected function insertLogo(&$page, $store = null)
    {
       
        $this->y = $this->y ? $this->y : 815;
        $image = Mage::getStoreConfig('sales/identity/logo', $store);
        if ($image) {
            $image = Mage::getBaseDir('media') . '/sales/store/logo/' . $image;
            if (is_file($image)) {
                $image       = Zend_Pdf_Image::imageWithPath($image);
                $top         = 820; //top border of the page
                $widthLimit  = 180; //half of the page width
                $heightLimit = 50; //assuming the image is not a "skyscraper"
                $width       = 90;
                $height      = 25;
 
                $y1 = $top - $height;
                $y2 = $top;
                $x1 = 25;
                $x2 = $x1 + $width;      
                       
                //coordinates after transformation are rounded by Zend
                $page->drawImage($image, $x1, $y1, $x2, $y2);  
               $this->y = $y1 - 10;
            }
        }
    }
    
    public function getPdf($invoice= array())
    {
         
        $this->_beforeGetPdf();
        $this->_initRenderer('sashas_invoice');
    	$this->_invoice_type=$invoice->getType();
    	 
        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
 
		$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
		$pdf->pages[] = $page;                 
 
		/* Add image */		
		$this->insertLogo($page);
    
		/* Add address */
		$this->insertAddress($page);
    
		/* Add head */
		$this->insertOrder($page, $invoice);    
		    			            
		/* Add table */
		$this->_setFontRegular($page);
		$page->setLineWidth(0.5);    
		$page->setFillColor(new Zend_Pdf_Color_GrayScale(1));
		$page->drawRectangle(25, $this->y, 570, $this->y -15);
		$this->y -=10;
    
		/* Add table head */
		$page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
		$page->drawText(Mage::helper('sales')->__('Date'), 35, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Description'), 85, $this->y, 'UTF-8');           
            $page->drawText(Mage::helper('sales')->__('Time/Price'), 535, $this->y, 'UTF-8');
  
            $this->y -=15; 
            $this->_setFontRegular($page);
            /* Add body */
            foreach ($invoice->getItems() as $item){
                if ($this->y < 15) {
                    $page = $this->newPage(array('table_header' => true));
                }                
                /* Draw item */                              
                $page = $this->_drawItem($item, $page, $invoice);
            }
    
		/* Add totals */            
		$page = $this->insertTotals($page, $invoice);
              
        $this->_afterGetPdf();
    
        return $pdf;
    }
    
    /**
     * Create new page and assign to PDF object
     *
     * @param array $settings
     * @return Zend_Pdf_Page
     */
    public function newPage(array $settings = array())
    {
        /* Add new table head */
        $page = $this->_getPdf()->newPage(Zend_Pdf_Page::SIZE_A4);
        $this->_getPdf()->pages[] = $page;
        $this->y = 800;
    
        if (!empty($settings['table_header'])) {
            $this->_setFontRegular($page);
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
            $page->setLineColor(new Zend_Pdf_Color_GrayScale(0));
            $page->setLineWidth(0.5);
            $page->drawRectangle(25, $this->y, 570, $this->y-15);
            $this->y -=10;
               
            $page->drawText(Mage::helper('sales')->__('Date'), 35, $this->y, 'UTF-8');
            $page->drawText(Mage::helper('sales')->__('Description'), 85, $this->y, 'UTF-8');           
            $page->drawText(Mage::helper('sales')->__('Time/Price'), 535, $this->y, 'UTF-8');
             
    
            $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
            $this->y -=20;
        }
    
        return $page;
    }  

    protected function _drawItem(Varien_Object $item, Zend_Pdf_Page $page, Varien_Object $invoice)
    {        
        $renderer = $this->_getRenderer('default');
        $renderer->setInvoice($invoice);
        $renderer->setItem($item);
        $renderer->setPdf($this);
        $renderer->setPage($page);
        $renderer->setRenderedModel($this);
        $renderer->draw();

        return $renderer->getPage();
    }    
    
    
    protected function insertOrder(&$page, $obj, $putOrderId = true)
    {
        $invoice = $obj; 
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $font = $this->_setFontRegular($page, 10);
        $page->setLineWidth(0);          
        $page->drawText(Mage::helper('sales')->__('Invoice # ').$invoice->getInvoiceId(), 35, $this->y, 'UTF-8');
     
        $page->drawText(Mage::helper('sales')->__('Invoice Date: ') . date( 'n/d/Y', strtotime( 'now')), 35, $this->y-10, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('Paypal Email: ') . 'asashas@mail.ru', 35, $this->y-20, 'UTF-8');
 
        $this->y = $this->y-40;         
    }
   
    
    /**
     * Insert totals to pdf page
     *
     * @param  Zend_Pdf_Page $page
     * @param  Mage_Sales_Model_Abstract $source
     * @return Zend_Pdf_Page
     */
    protected function insertTotals($page, $source){
        $invoice= $source;         
        $lineBlock = array(
                'lines'  => array(),
                'height' => 15
        );
        foreach ($invoice->getTotals() as $total) {            
            if (strpos($total->getAmount(), ":")===false )
                $amount=Mage::helper('core')->currency($total->getAmount(), true, false);
            else
                $amount=Mage::helper('core/string')->str_split($total->getAmount(),10);
             
			$lineBlock['lines'][] = array(
				array(
					'text' => $total->getlabel(), 
					'feed' => 475, 
					'align' => 'right', 
					'font_size' => 10, 
					'font' => 'bold'
				),
				array(
					'text'      => $amount,
					'feed'      => 565,
					'align'     => 'right',
					'font_size' => 10,
					'font'      => 'bold'
					),
			);		              
        }
        die('invoice.php');
        $this->y -= 20;
        $page = $this->drawLineBlocks($page, array($lineBlock));
        return $page;
    }
}