<?php 
/**
 * RealInsight Parent Functions
 *
 * This file contains helper functions that act as custom template tags. Others are attached to 
 * action and filter hooks in WordPress to change core functionality.
 * 
 * @link http://codex.wordpress.org/Template_Tags
 * @link http://codex.wordpress.org/Function_Reference/add_action
 * @link http://codex.wordpress.org/Function_Reference/add_filter
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes 
 * 
 * @package WordPress
 * @subpackage RealInsight
 * @since RealInsight 1.0
 */


 
  
/**
 * Include some theme functions
 * 
 * @since RealInsight 1.0
 */
if ( ! class_exists( '\RealInsight\Theme_Functions' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/class_theme-functions.php';
}

if ( ! class_exists( 'MetaBox_PreFooter' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/class_mb_prefooter.php';
}

if ( ! class_exists( 'MetaBox_SubHeader' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/class_mb_subheader.php';
}

if ( ! class_exists( 'MetaBox_ParentRedirect' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/class_mb_parent_redirect.php';
}

show_admin_bar(false);

/**
 * Theme Setup
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since RealInsight 1.0
 */
if ( ! function_exists( 'rinsight_theme_setup' ) ) 
{
	function rinsight_theme_setup()
	{
			
		add_post_type_support( 'page', 'excerpt' );
		
		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 672, 372, true );
		add_image_size( 'archive-thumb', 260, 160, true );
		
		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'primary'   => __( 'Top primary menu', 'rinsight' ),
				'secondary' => __( 'Secondary menu in header', 'rinsight' ),
			) 
		);
				
		 // Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );		
		
		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );	
	}

};
add_action( 'after_setup_theme', 'rinsight_theme_setup' );


/**
 * Register Widget areas
 * 
 * @since RealInsight 1.0
 */
function rinsight_widgets_init() 
{	
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'rinsight' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the right.', 'rinsight' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
/*
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'rinsight' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears in the footer section of the site.', 'rinsight' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
*/
}
add_action( 'widgets_init', 'rinsight_widgets_init' );


if ( ! function_exists( 'debug' ) ) 
{
	function debug($var){
		echo "\n<pre style=\"text-align:left;\">";
		if( is_array($var) || is_object($var)){
			print_r($var);
		} else {
			var_dump($var);
		}
		echo "</pre>\n";
	}
}

/**
 * A lightweight nav menu for sibling pages
 */
function show_siblings($post_id = null){
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	if( in_the_loop() ) {
		global $post;
		$current_post = $post;
	} else {
		$current_post = get_post($post_id);		
	}

	if( !$current_post || is_null($current_post) || !$current_post->post_parent ) {
		return;
	}

	$siblings = get_pages(array(
		'sort_order' => 'ASC',
		'sort_column' => 'menu_order',
		'parent' => $current_post->post_parent,
	));


	if( empty($siblings) ){
		return;
	}

	$out = '<ul id="left-menu" class="sibling-list">';
	
	foreach( $siblings as $sib ){
		$link = get_permalink($sib->ID);		
		if( $sib->ID === $post_id ){
			$out .= '<li><a href="'.$link.'" class="active">' . $sib->post_title . '</a></li>';
		} else {
			$out .= '<li><a href="'.$link.'">' . $sib->post_title . '</a></li>';
		}
	}
	
	$out .= '</ul>';

	echo $out;
}




function add_nivo_menuclass($atts, $item, $args) {
	if( is_array($atts) && !empty($atts['rel']) && $atts['rel'] = 'nivo' ) {
		$atts['class'] = 'lightbox';
		$atts['data-lightbox-type'] = 'inline';
	}
	
	return $atts;
    
}
add_filter('nav_menu_link_attributes','add_nivo_menuclass', 0,3);
