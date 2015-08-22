<?php 
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Invoice
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Invoice_Adminhtml_InvoiceController extends Mage_Adminhtml_Controller_Action {
	
	public function _initAction()
	{
		$this->loadLayout()->_setActiveMenu('sales') ->_addBreadcrumb(
                Mage::helper('invoice')->__('Invoice'),
                Mage::helper('invoice')->__('Upload')
            );		 
	
		return $this;
	}
 
	
	/**
	 * Check for is allowed
	 *
	 * @return boolean
	 */
	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('sales/invoice');
	}	
	
	public function indexAction()
	{
		$this->_initAction();
		$block = $this->getLayout()->createBlock('invoice/adminhtml_uploadcontainer','invoice_upload_form_container');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
	
	public function saveAction()
	{  
 
	    /*File*/
		if(isset($_FILES['filecsv']['name']) and (file_exists($_FILES['filecsv']['tmp_name']))) {
			try {
				$uploader = new Varien_File_Uploader('filecsv');
				$uploader->setAllowedExtensions(array('csv'));
				$uploader->setAllowRenameFiles(true);
				$path = Mage::getBaseDir('var') . DS.'import'.DS;
				$filename=$uploader->getCorrectFileName($_FILES['filecsv']['name']);
				if (file_exists($path.$filename))
					unlink ($path.$filename);
				$uploader->save($path, $filename);
				$invoice=$this->_getHelper()->processfile($path.$filename);
				/*Generate Invoice Pdf*/				 
				$pdf = Mage::getModel('invoice/pdf_invoice')->getPdf($invoice);
				 
				return $this->_prepareDownloadResponse(
				        'sashasitsupport_invoice_'.$invoice->getInvoiceId().'.pdf', $pdf->render(),
				        'application/pdf'
				);				
				/*Generate Invoice Pdf*/
			}catch(Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		} else {
			Mage::getSingleton('adminhtml/session')->addError("File wasn't uploaded");
		}
		$this->_redirect('*/*/');
	}
	
	/**
	 * Retrieve base admihtml helper
	 *
	 * @return Mage_Adminhtml_Helper_Data
	 */
	protected function _getHelper()
	{
		return Mage::helper('invoice');
	}
		
	
}
