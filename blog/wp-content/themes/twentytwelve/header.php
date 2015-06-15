<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
<?php 
$layout = Mage::app('default')->setCurrentStore( Mage::app()->getStore()->getId() )->getLayout();
$layout->getUpdate()->addHandle('default')->load();
$layout->generateXml()->generateBlocks();
$head=$layout->getBlock('head');
echo $head->toHtml();
 
?>
</head>
 
<body <?php body_class(); ?>>

 <?php 
 $topmenu=$layout->getBlock('header');
 $topmenu->unsetChild('store_language');
 $new_block=$layout->createBlock('wordpress/page_switch');
 $topmenu->setChild('store_language',$new_block);
 $topmenu->unsetChild('topSearch');
 echo $topmenu->toHtml();
 
 ?>			

 
<div id="page" class="hfeed site"> 
	<div id="main" class="wrapper">
	 <?php 
 $messages=$layout->getBlock('global_messages');
 echo $messages->toHtml();
 ?>