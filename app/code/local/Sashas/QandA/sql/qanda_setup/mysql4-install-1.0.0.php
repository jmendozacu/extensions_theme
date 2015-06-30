<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

$installer = $this;
$installer->startSetup();
 
$table = $installer->getConnection()->newTable($this->getTable('qanda/questions'))
	->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
	        'unsigned' => true,
	        'nullable' => false,
	        'primary' => true,
	        'identity' => true,
	), 'Question ID')
	->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
	        'nullable' => true,
	), 'Product ID')
	->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
	        'nullable' => true,
	), 'Store ID')
	->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
	        'nullable' => true,
	), 'Customer ID')
	->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
	        'nullable' => true,
	        'default' => 0,
	), 'Status')
	->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
	        'nullable' => true,
	), 'Name')
	->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
	        'nullable' => true,
	), 'Email')
	->addColumn('question', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
	        'nullable' => true,
	), 'Question')
	->addColumn('answer', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
	        'nullable' => true,       
	), 'Answer')
	->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
	        'nullable' => true,
	), 'Created Date')
	->addForeignKey($installer->getFkName('qanda/questions', 'product_id', 'catalog/product', 'entity_id'),
	        'product_id', $installer->getTable('catalog/product'), 'entity_id',
	        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
->setComment('Questions and Answers Table');
$installer->getConnection()->createTable($table);
                            
$installer->endSetup();