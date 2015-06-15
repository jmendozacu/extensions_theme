<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Magapp
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license    http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

$installer = $this;

$installer->startSetup();
 
$table = $installer->getConnection()->newTable($installer->getTable('magapp/notifications'))
->addColumn('notification_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned' => true,
		'nullable' => false,
		'primary' => true,
		'identity' => true,
), 'Notification ID')
->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable' => false,
), 'Order Entity ID')
->addColumn('increment_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
		'nullable' => false,
), 'Order Increment ID')
->addColumn('grand_total', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
		'nullable' => false,
), 'Order Grand Total')
->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
		'nullable' => false,
), 'Order Status')
->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable' => false,
), 'Store ID')
 
->setComment('Magapp Notifications Table');
$installer->getConnection()->createTable($table);
 
$installer->endSetup();