<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Minify
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license    http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

require_once  Mage::getBaseDir('lib').DS.'HtmlMin'.DS.'HTML.php';

class Sashas_Minify_Model_Observer {
    
    public function minifyBlockHtml(Varien_Event_Observer $observer) {
        
        $isActive=Mage::getStoreConfig('minify/minify_group/enable_html');
        if (!$isActive)
            return;
        
        
        Mage::getSingleton('core/session', array('name'=>'adminhtml'));
        if(Mage::getSingleton('admin/session')->isLoggedIn()){
            return;
        }
                
        $transport = $observer->getTransport();
        $html= $transport->getHtml();
        $html=MinifyHTML::minify($html,array('xhtml'=>1, 'jsCleanComments'=>1));
        $transport->setHtml($html);
    }
}
