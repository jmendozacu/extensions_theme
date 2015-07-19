<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Block_Adminhtml_Catalog_Product_Tab_Questions  extends Mage_Adminhtml_Block_Widget_Grid {
	
    public function __construct()
    {
        parent::__construct();
        $this->setId('questions_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('qanda/questions') ->getResourceCollection();
        $product_id =Mage::app()->getRequest()->getParam('id');
        $collection->addFieldToFilter('product_id',array('qa'=>$product_id));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('qanda')->__('ID'),             
            'width'     => '50px',
            'index'     => 'entity_id',
            'type'  	=> 'number',
        ));
                 
        $this->addColumn('customer_name', array(
        	'header'    => Mage::helper('qanda')->__('Customer Name'),
        	'align'     =>'center',
        	'index'     => 'name',
            'renderer'  => 'Sashas_QandA_Block_Adminhtml_Renderer_Customer',
        	'width' 	=> '150px',
        ));        
                
        $this->addColumn('customer_email', array(
        	'header'    => Mage::helper('qanda')->__('Customer Email'),
        	'align'     =>'center',
        	'index'     => 'email',
        	'width' 	=> '150',
        ));
 
 
        $this->addColumn('question', array(
                'header'    => Mage::helper('qanda')->__('Question'),
                'align'     =>'left',                
                'index'     => 'question',
        ));                
  
        $this->addColumn('status', array(
                'header'    => Mage::helper('qanda')->__('Status'),
                'align'     =>'center',
                'type' => 'options',
                'index'     => 'status',
                'width'     => '100px',
                'options' =>  array(
                    0 => Mage::helper('qanda')->__('New'),
                	1 => Mage::helper('qanda')->__('Answered'),
                    2 => Mage::helper('qanda')->__('Not Answered'),                	
            	),
        ));

        $this->addColumn('store_id', array(
                'header'=> Mage::helper('qanda')->__('Store'),
                'width' => '100px',
                'sortable'  => false,
                'index'     => 'store_id',
                'type'      => 'options',
                'align'     =>'center',
                'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
        ));        
        
        $this->addColumn('created_at', array(
                'header'    => Mage::helper('qanda')->__('Date Created'),
                'align'     =>'center',
                'type'      => 'datetime',
                'width' => '150px',
                'index'     => 'created_at',
        ));
        
        $this->addColumn('action',array(
                'header'    => Mage::helper('qanda')->__('Action'),
                 'width'     => '50px',
                 'type'      => 'action',
                 'align'     =>'center',
                 'getter'     => 'getId',
                 'actions'   => array(
 					array(
						'caption' => Mage::helper('qanda')->__('Edit'),
						'url'     => array(
						'base'=>'adminhtml/qanda/edit',							
						),
 					     'target'=>'_blank',
						'field'   => 'id')
					),
				'filter'    => false,
				'sortable'  => false,
                         
       ));
                
        return parent::_prepareColumns();
    }
    
 
    public function getRowUrl($row)
    {
        return 'javascript:void(0);';
    }

    
    protected function _prepareMassaction()
    {    	 
    	return $this;
    }    
}