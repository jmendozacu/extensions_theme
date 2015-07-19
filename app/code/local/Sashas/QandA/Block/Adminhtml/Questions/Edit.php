<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Block_Adminhtml_Questions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		$this->_objectId = 'id';
		$this->_blockGroup='qanda';
		$this->_controller = 'adminhtml_questions';
	
		parent::__construct();
	
		$this->_updateButton('save', 'label', Mage::helper('qanda')->__('Save Question'));
		$this->_updateButton('delete', 'label', Mage::helper('qanda')->__('Delete Question'));
	
		$this->_addButton('saveandcontinue', array(
				'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
				'onclick'   => 'saveAndContinueEdit()',
				'class'     => 'save',
		), -100);
		
		$this->_formScripts[] = " 		
			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";		
		$this->_removeButton('reset');
	}
	
	/**
	 * Get edit form container header text
	 *
	 * @return string
	 */
	public function getHeaderText()
	{
		if (Mage::registry('qanda_question')->getId()) {
			return Mage::helper('qanda')->__("Answer Question '%s'", $this->escapeHtml(Mage::registry('qanda_question')->getQuestion()));
		}		 
	}
	
	/**
	 * Get form action URL
	 *
	 * @return string
	 */
	public function getFormActionUrl()
	{
	    if ($this->hasFormActionUrl()) {
	        return $this->getData('form_action_url');
	    }
	    return $this->getUrl('*/qanda/save');
	}
}
