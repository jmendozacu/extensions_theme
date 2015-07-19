<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Block_Adminhtml_Questions_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	
	/**
	 * Init form
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setId('questions_form');
		$this->setTitle(Mage::helper('qanda')->__('Question'));
	}
		
	protected function _prepareForm()
	{
		$model = Mage::registry('qanda_question');
	
		$form = new Varien_Data_Form(
				array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
		);
	
		$form->setHtmlIdPrefix('qanda_');
	
		$fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('qanda')->__('General'), 'class' => 'fieldset-wide'));
	
		if ($model->getEntityId()) {
			$fieldset->addField('entity_id', 'hidden', array(
					'name' => 'entity_id',
			));
			$_product=Mage::getModel('catalog/product')->load($model->getProductId());
		}  		
		 				
		$fieldset->addField('name', 'text', array(
				'name'      => 'name',
				'label'     => Mage::helper('qanda')->__('Customer Name'),
				'title'     => Mage::helper('qanda')->__('Customer Name'),				 	
				'style'   	=> "max-width:275px",
		));
		
		$fieldset->addField('email', 'text', array(
		        'name'      => 'email',
		        'label'     => Mage::helper('qanda')->__('Customer Email'),
		        'title'     => Mage::helper('qanda')->__('Customer Email'),		        
		        'style'   	=> "max-width:275px",
		));
				
		$fieldset->addField('product_sku', 'text', array(
		        'name'      => 'product_sku',
		        'label'     => Mage::helper('qanda')->__('Product Sku'),
		        'title'     => Mage::helper('qanda')->__('Product Sku'),		      
		        'value'		=> $_product->getSku(),
		        'readonly' => true,
		        'style'   	=> "max-width:275px",
		));		
			  	 
	 	$fieldset->addField('status', 'select', array(
	 			'name'  	=> 'status',
	 	        'title'     => Mage::helper('qanda')->__('Status'),
	 			'label'    	=> Mage::helper('qanda')->__('Status'),		 		
	 		 	'values'    => array(
                    0 => Mage::helper('qanda')->__('New'),
                	1 => Mage::helper('qanda')->__('Answered'),
                    2 => Mage::helper('qanda')->__('Not Answered'),                	
            	), 			
	 	));	  
	 	
	 	$fieldset->addField('question', 'textarea', array(
	 			'name'      => 'city',
	 			'label'     => Mage::helper('qanda')->__('City'),
	 			'title'     => Mage::helper('qanda')->__('City'),	 			 	 			 
	 	));	 

	 	$fieldset->addField('answer', 'textarea', array(
	 			'name'      => 'answer',
	 			'label'     => Mage::helper('qanda')->__('Answer'),
	 			'title'     => Mage::helper('qanda')->__('Answer'),
	 			'required'  => true,	 		 
	 	));	 	
		
		$form->setValues($model->getData());
		$form->addValues(array('product_sku'=>$_product->getSku()));
		
		$form->setUseContainer(true);
		$this->setForm($form);
	
		return parent::_prepareForm();
	}

	
}