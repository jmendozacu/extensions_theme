<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
<?php 
$layout = Mage::app('default')->setCurrentStore( Mage::app()->getStore()->getId() )->getLayout();
$layout->getUpdate()->addHandle('default')->load();
$layout->generateXml()->generateBlocks();
 $footer=$layout->getBlock('footer');
 echo $footer->toHtml();
?>	
 
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>