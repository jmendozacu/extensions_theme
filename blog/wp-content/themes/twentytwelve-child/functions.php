<?php 
function theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

add_filter( 'comment_form_defaults', 'remove_comment_form_allowed_tags' );
function remove_comment_form_allowed_tags( $defaults ) {
    
    	$defaults['comment_notes_after'] = '';
    	return $defaults;
    
    }
    
?>