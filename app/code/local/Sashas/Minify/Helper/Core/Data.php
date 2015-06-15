<?php
/**
 * @author		Sashas
 * @category    Sashas
 * @package     Sashas_Minify
 * @copyright   Copyright (c) 2015 Sashas IT Support Inc. (http://www.sashas.org)
 * @license    http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Sashas_Minify_Helper_Core_Data extends Mage_Core_Helper_Data {
    
    /* (non-PHPdoc)
     * @see Mage_Core_Helper_Data::mergeFiles()
     */
    public function mergeFiles(array $srcFiles, $targetFile = false, $mustMerge = false,
        $beforeMergeCallback = null, $extensionsFilter = array())
    {
        try {
            // check whether merger is required
            $shouldMerge = $mustMerge || !$targetFile;
            if (!$shouldMerge) {
                if (!file_exists($targetFile)) {
                    $shouldMerge = true;
                } else {
                    $targetMtime = filemtime($targetFile);
                    foreach ($srcFiles as $file) {
                        if (!file_exists($file) || @filemtime($file) > $targetMtime) {
                            $shouldMerge = true;
                            break;
                        }
                    }
                }
            }
    
            // merge contents into the file
            if ($shouldMerge) {
                if ($targetFile && !is_writeable(dirname($targetFile))) {
                    // no translation intentionally
                    throw new Exception(sprintf('Path %s is not writeable.', dirname($targetFile)));
                }
    
                // filter by extensions
                if ($extensionsFilter) {
                    if (!is_array($extensionsFilter)) {
                        $extensionsFilter = array($extensionsFilter);
                    }
                    if (!empty($srcFiles)){
                        foreach ($srcFiles as $key => $file) {
                            $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            if (!in_array($fileExt, $extensionsFilter)) {
                                unset($srcFiles[$key]);
                            }
                        }
                    }
                }
                if (empty($srcFiles)) {
                    // no translation intentionally
                    throw new Exception('No files to compile.');
                }
    
                $data = '';
                foreach ($srcFiles as $file) {
                    if (!file_exists($file)) {
                        continue;
                    }
                    $contents = file_get_contents($file) . "\n";
                    if ($beforeMergeCallback && is_callable($beforeMergeCallback)) {
                        $contents = call_user_func($beforeMergeCallback, $file, $contents);
                    }
                    $data .= $contents;
                }
                if (!$data) {
                    // no translation intentionally
                    throw new Exception(sprintf("No content found in files:\n%s", implode("\n", $srcFiles)));
                }
                if ($targetFile) {
                    /*Sashas*/
                    $isActiveCss=Mage::getStoreConfig('minify/minify_group/enable_css');
                    $isActiveJs=Mage::getStoreConfig('minify/minify_group/enable_js');
                    
                    if ($fileExt=='css' && $isActiveCss)
                        $data=$this->_YuiMinify($data);
                    elseif ($fileExt=='js' && $isActiveJs)
                         $data=$this->_JsMin($data);                                       
                    /*Sashas*/
                    file_put_contents($targetFile, $data, LOCK_EX);
                } else {
                    return $data; // no need to write to file, just return data
                }
            }
    
            return true; // no need in merger or merged into file successfully
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return false;
    }
    
    protected function _YuiMinify($data){
        
        Mage::getSingleton('core/session', array('name'=>'adminhtml'));
        if(Mage::getSingleton('admin/session')->isLoggedIn()){
            return $data;
        }
        
        require_once  Mage::getBaseDir('lib').DS.'CssMin'.DS.'cssmin.php';
        
        $compressor = new CSSmin();
        $compressor->set_memory_limit('256M');
        $compressor->set_max_execution_time(120);
        $output = $compressor->run($data);
        return $output;
    }
    
    protected function _JsMin($data) {
        
        Mage::getSingleton('core/session', array('name'=>'adminhtml'));              
        if(Mage::getSingleton('admin/session')->isLoggedIn()){
            return $data;
        }
        
        $isActivePlusJs=Mage::getStoreConfig('minify/minify_group/js_minifyplus');
        if ($isActivePlusJs) {
            require_once  Mage::getBaseDir('lib').DS.'JsMin'.DS.'JSMinPlus.php';
            $output=JSMinPlus::minify($data);
        }else {
            require_once  Mage::getBaseDir('lib').DS.'JsMin'.DS.'JSMin.php';
            $output=JSMin::minify($data);
        }
 
        return $output;
    }
        
}