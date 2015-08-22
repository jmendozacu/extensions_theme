<?php 
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Invoice
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Invoice_Block_Adminhtml_Uploadcontainer  extends Mage_Adminhtml_Block_Widget_Form_Container{
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = 'id';
		$this->_blockGroup = 'invoice';
		$this->_controller = 'adminhtml_upload';
		$this->_mode='';
		$this->_updateButton('save', 'label', Mage::helper('invoice')->__('Upload And Generate'));
		$this->_updateButton('save', 'onclick', 'invoice_upload_form.submit();');
	}
	
	public function getHeaderText()
	{
		return Mage::helper('invoice')->__('Invoice Upload');
	}
	
	protected function _prepareLayout()
	{
		if ($this->_blockGroup && $this->_controller) {
			$this->setChild('form', $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . 'form'));
		}
		return  Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
	}
	
}