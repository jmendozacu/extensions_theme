<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Extensions
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class Sashas_Extensions_Block_Crosssell extends Mage_Checkout_Block_Cart_Crosssell {
    
    protected $_maxItemCount = 100;
    
    /**
     * Get crosssell items
     *
     * @return array
     */
    public function getItems()
    {  
        $items = $this->getData('items');
        if (is_null($items)) { 
            $items = array();
            $ninProductIds = $this->_getCartProductIds();
            if ($ninProductIds) {  
                $lastAdded = (int) $this->_getLastAddedProductId();
                if ($lastAdded) {
                    $collection = $this->_getCollection()
                    ->addProductFilter($lastAdded);
                    if (!empty($ninProductIds)) {
                        $collection->addExcludeProductFilter($ninProductIds);
                    }
                    
                    $collection->getSelect()->order('rand()'); 
                    $collection->load();
                     
                    foreach ($collection as $item) {
                        $ninProductIds[] = $item->getId();
                        $items[] = $item;
                    }
                }
    
                if (count($items) < $this->_maxItemCount) {
                    $filterProductIds = array_merge($this->_getCartProductIds(), $this->_getCartProductIdsRel());
                    $collection = $this->_getCollection()
                    ->addProductFilter($filterProductIds)
                    ->addExcludeProductFilter($ninProductIds)
                    ->setPageSize($this->_maxItemCount-count($items))
                    ->setGroupBy()
                    ->setPositionOrder();
                    
                    $collection->getSelect()->order('rand()');
                    $collection->load();
                    
                    foreach ($collection as $item) {
                        $items[] = $item;
                    }
                }
    
            }
    
            $this->setData('items', $items);
        }
        return $items;
    }    
}