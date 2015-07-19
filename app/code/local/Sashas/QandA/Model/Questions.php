<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_QandA
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_QandA_Model_Questions extends Mage_Core_Model_Abstract
{
    const CACHE_TAG     = 'qanda_questions';
    protected $_cacheTag= 'qanda_questions';

    protected function _construct()
    {
        $this->_init('qanda/questions');
    }

 
}
