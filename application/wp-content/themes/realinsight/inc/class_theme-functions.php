<?php

namespace RealInsight;

/**
 * No direct access
 */
defined( 'ABSPATH' ) or die( 'Nothing here!' );


/**
 * Theme Functions Class
 */

class Theme_Functions
{

	const THEME_PREFIX = 'rinsight';

	/**
	 * The constructor
	 *
	 * Initialize & hook into WP
	 *
	 * @access  public
	 * @since   1.0
	 * @return  void
	 */
	public function __construct()
	{
		add_action( 'wp_head', array($this,'show_favicon') );
		add_action( 'admin_menu', array($this, 'no_theme_customize') );
		add_action( 'init', array($this, 'register_theme_styles') );
		add_action( 'init', array($this, 'register_theme_scripts') );
		add_action( 'wp_enqueue_scripts', array($this, 'load_front_styles') );
		add_action( 'wp_enqueue_scripts', array($this, 'load_front_scripts') );
		add_filter( 'wp_title', array($this, 'wp_title'), 10,2 );

		#add_action( 'login_enqueue_scripts', array($this, 'load_login_styles') );
		#add_action( 'wp_head', array($this, 'add_social_meta') );
		
		add_filter( 'nav_menu_css_class', array($this, 'nav_active_class'), 10 , 2);
	}
	
	
	/**
	 * Add the Bootstrap "active" nav link class
	 *
	 * @since RealInsight 1.0
	 */
	public function nav_active_class($classes, $item)
	{
		global $wp_query;
		$queried_object = $wp_query->get_queried_object();
		$queried_object_id = (int) $wp_query->queried_object_id;

		if( $item->object_id == $queried_object_id ){ $classes[] = "active"; }
		return $classes;
	}


	/**
	 * Retrieve the site's theme avatar
	 *
	 * Used for social media meta information
	 * @since RealInsight 1.0
	 */
	protected function _get_site_avatar_url()
	{
		$theme_img_dir = apply_filters('theme_img_dir', 'img');	
		$theme_avatar_name = apply_filters('theme_avatar_name', 'site_avatar.png');
		$avatar_url = '';
		
		$child_avatar_path = get_stylesheet_directory() . '/'. $theme_img_dir .'/' . $theme_avatar_name;
		if( file_exists( $child_avatar_path ) ) {
			$avatar_url = get_stylesheet_directory_uri() . '/'. $theme_img_dir .'/' . $theme_avatar_name;
		}
		
		if( '' === $avatar_url ){
			$parent_avatar_path = get_template_directory() . '/'. $theme_img_dir .'/' . $theme_avatar_name;
			if( file_exists( $parent_avatar_path ) ) {
				$avatar_url = get_template_directory_uri() . '/'. $theme_img_dir .'/' . $theme_avatar_name;
			}
		}
		
		return apply_filters('theme_avatar_url', $avatar_url);
	}
	
	
	/**
	 * Chunk a string an XX characters
	 * 
	 * @since RealInsight 1.0
	 */
	public function _abbreviate($text, $max = '95') 
	{
		if ( strlen($text) <= $max ){
			return $text;
		}
		return substr($text, 0, $max-3) . '&#8230;';
	}	


	/**
	 * Add Social meta tags
	 *
	 * Facebook & Twitter
	 */
	public function add_social_meta() 
	{

		global $wp_query;
		$meta_set = false;
		$meta = $pagelink = $pagetitle = $pagecontent = '';
		$avatar_url = $this->_get_site_avatar_url();

		// if we're on the front page or the blog index
		if( is_front_page() || is_home() ){
			$pagelink = site_url();
			$pagetitle = get_bloginfo('name');
			$pagecontent = get_bloginfo('description');
			$meta_set = true;
		}

		// if we're on a Page, a Post 
		if( is_page() || is_singular('post') ){
			$pagelink = get_permalink( $wp_query->queried_object->ID );
			$pagetitle = $this->_abbreviate($wp_query->queried_object->post_title, '95');
			$pagecontent = ( '' != $wp_query->queried_object->post_excerpt ) ? $wp_query->queried_object->post_excerpt : $wp_query->queried_object->post_content;
			$meta_set = true;
		}

		if( is_page() ){
			$post_id = $wp_query->get_queried_object_id();
			$pagelink = home_url('?p=' . $post_id);
		}

		if( true === $meta_set ) {

			$pagetitle = wp_kses($pagetitle, $allowed_html=array());
			$pagetitle = esc_attr__($pagetitle);
			$pagecontent = wp_kses($pagecontent, $allowed_html=array());
			$pagecontent = $this->_abbreviate($pagecontent, '297');
			$pagecontent = esc_attr__($pagecontent);

			// facebook
			$meta .= "\n".'<meta property="og:type" content="website" />';
			$meta .= "\n".'<meta property="og:url" content="'.$pagelink.'" />';
			$meta .= "\n".'<meta property="og:title" content="'. $pagetitle .'" />';
			$meta .= "\n".'<meta property="og:description" content="'. $pagecontent .'" />';
			$meta .= "\n".'<meta property="og:image" content="'.$avatar_url.'" />';

			// twitter
			$meta .= "\n".'<meta name="twitter:card" content="summary" />';
			$meta .= "\n".'<meta name="twitter:url" content="'.$pagelink.'" />';
			$meta .= "\n".'<meta name="twitter:title" content="'. $pagetitle .'" />';
			$meta .= "\n".'<meta name="twitter:description" content="'.$pagecontent.'" />';
			$meta .= "\n".'<meta name="twitter:image:src" content="'.$avatar_url.'" />';
			$meta .= "\n";

		}


		echo $meta;
	}


	/**
	 * Filter the page title.
	 *
	 * Create a nicely formatted and more specific title element text for output
	 * in head of document, based on current view.
	 *
	 * @since 1.0
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	public function wp_title( $title, $sep ) 
	{
		global $paged, $page;

		if ( is_feed() ) {
			return $title;
		}

		// Add the site name.
		$title .= get_bloginfo( 'name', 'display' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title = "$title $sep $site_description";
		}

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 ) {
			$title = "$title $sep " . sprintf( __( 'Page %s', 'rinsight' ), max( $paged, $page ) );
		}

		return $title;
	}

	
	/**
	 * Load theme scripts
	 *
	 * @since 1.0
	 */
	public function load_front_scripts()
	{
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_script( self::THEME_PREFIX . '-main');
	}


	/**
	 * Load theme styles
	 *
	 * @since 1.0
	 */
	public function load_front_styles()
	{
		$font_url = $this->_get_font_url();

		if ( ! empty( $font_url ) ) {
			wp_enqueue_style( self::THEME_PREFIX .'-fonts', esc_url_raw( $font_url ), array(), null );
		}

		wp_enqueue_style( self::THEME_PREFIX . '-main' );
		
	}


	/**
	 * Load theme styles
	 *
	 * @since 1.0
	 */
	public function load_login_styles()
	{
		wp_enqueue_style( self::THEME_PREFIX . '-login' );
		wp_enqueue_script( self::THEME_PREFIX . '-login');
	}


	/**
	 * Register parent scripts
	 *
	 * @since 1.0
	 */
	public function register_theme_scripts()
	{

		wp_register_script(
			'google-maps',
			'https://maps.googleapis.com/maps/api/js?v=3.9',
			array( 'jquery'),
			'3.9',
			true
		);
		
		wp_register_script(
			'light-slider',
			get_template_directory_uri()  . '/js/jquery.lightSlider.min.js',
			array( 'jquery'),
			'1.1.1',
			true
		);	

		wp_register_script(
			'nivo-lightbox',
			get_template_directory_uri()  . '/js/nivo-lightbox.min.js',
			array( 'jquery'),
			'1.2',
			true
		);	

		wp_register_script(
			'backgroundcover',
			get_template_directory_uri()  . '/js/jquery.backgroundcover.min.js',
			array( 'jquery'),
			'1.0',
			true
		);			

		wp_register_script(
			self::THEME_PREFIX . '-main',
			get_template_directory_uri()  . '/js/script.js',
			array('jquery','google-maps','light-slider','nivo-lightbox','backgroundcover'),
			'1.0',
			true
		);

	}


	/**
	 * Register parent styles
	 *
	 * @since 1.0
	 */

	public function register_theme_styles()
	{

		$min = ( defined('WP_DEBUG') && WP_DEBUG ) ? '' : '.min';
		$min = '';

		wp_register_style(
			'reset',
			get_template_directory_uri()  . '/css/reset.css',
			array(),
			'2.0',
			'all'
		);

		wp_register_style(
			'lightSlider',
			get_template_directory_uri()  . '/css/lightSlider.css',
			array('reset'),
			'1.0',
			'all'
		);
		
		wp_register_style(
			'nivo-lightbox',
			get_template_directory_uri()  . '/css/nivo-lightbox.css',
			array('reset','lightSlider'),
			'1.2.0',
			'all'
		);	

		wp_register_style(
			'nivo-default',
			get_template_directory_uri()  . '/css/themes/default/default.css',
			array('reset','lightSlider','nivo-lightbox'),
			'1.0',
			'all'
		);			

		wp_register_style(
			self::THEME_PREFIX . '-main',
			get_template_directory_uri()  . '/style.css',
			array('reset', 'lightSlider','nivo-lightbox','nivo-default'),
			'1.0',
			'all'
		);
		

		wp_register_style(
			self::THEME_PREFIX . '-login',
			get_template_directory_uri()  . '/css/login.css',
			array(self::THEME_PREFIX . '-main'),
			'1.0',
			'all'
		);

	}


	/**
	 * Register Google Font url
	 *
	 * @access  prtected
	 * @since   1.0
	 */
	protected function _get_font_url()
	{
		$fonts_url = '';

		/* Translators: If there are characters in your language that are not
		 * supported by Fira Sans, translate this to 'off'. Do not translate into your
		 * own language.
		 */
		$fira_sans = _x( 'on', 'Fira Sans font: on or off', 'rinsight' );

		/* Translators: If there are characters in your language that are not
		 * supported by Crete Round, translate this to 'off'. Do not translate into your
		 * own language.
		 */
		$crete_round = _x( 'on', 'Crete Round font: on or off', 'rinsight' );

		if ( 'off' !== $fira_sans || 'off' !== $crete_round ) {
			$font_families = array();

			if ( 'off' !== $fira_sans ) {
				$font_families[] = 'Fira Sans:300,500,300italic,500italic';
			}

			if ( 'off' !== $crete_round ) {
				$font_families[] = 'Crete Round:400,400italic';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);
			$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
		}
		return $fonts_url;
	}


	/**
	 * Disable the Theme Customizer
	 *
	 * It's not needed for this theme.
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @uses wp_die()
	 */
	public function no_theme_customize()
	{
		global $pagenow;

		if( 'customize.php' === $pagenow ){
			wp_die(
				sprintf(
					__( 'The Theme Customizer is not compatible with your current theme: <strong>%s</strong>.', 'rinsight' ),
					wp_get_theme()
				),
				'',
				array('back_link' => true)
				);
		}
		remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Fthemes.php' );
	}


	/**
	 * Add the Site's Favicon to the site header
	*
	* @access public
	* @since 1.0
	*/
	public function show_favicon()
	{
		$favicon_url = apply_filters('favicon_url', get_stylesheet_directory_uri().'/img/icon/favicon.ico');
		echo "\n".'<link rel="shortcut icon" href="'.$favicon_url.'" type="image/x-icon">';
		echo "\n".'<link rel="icon" href="'.$favicon_url.'" type="image/x-icon">';
	}

}

$Theme_Functions = new Theme_Functions();