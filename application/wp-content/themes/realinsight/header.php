<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage RealInsight
 * @since RealInsight 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv-printshiv.min.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="preload">
	<img src="<?php echo get_stylesheet_directory_uri();?>/images/life-cycle-00.png" />
	<img src="<?php echo get_stylesheet_directory_uri();?>/images/life-cycle-01.png" />
	<img src="<?php echo get_stylesheet_directory_uri();?>/images/life-cycle-02.png" />
	<img src="<?php echo get_stylesheet_directory_uri();?>/images/life-cycle-03.png" />
	<img src="<?php echo get_stylesheet_directory_uri();?>/images/life-cycle-04.png" />
	<img src="<?php echo get_stylesheet_directory_uri();?>/images/life-cycle-05.png" />
	<img src="<?php echo get_stylesheet_directory_uri();?>/images/life-cycle-06.png" />
	<img src="<?php echo get_stylesheet_directory_uri();?>/images/life-cycle-07.png" />
</div>

<header>
	<div class="container clearfix">
		<a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo get_stylesheet_directory_uri();?>/images/logo.png"></a>
		<a id="mobile-menu" href="#"></a>
		<div id="links">
			<div class="top">
				<?php  
				$secondary_menu_args = array(					
					'container'=> false,
					'fallback_cb' => false,		
					'items_wrap' => '%3$s',
					'theme_location' => 'secondary'
				);  ?>				
				<ul>
					<li class="tagline"><em>Software for the Business Visionary</em></li>
					<?php wp_nav_menu($secondary_menu_args); ?>
				</ul>
			</div> <!-- /.top -->
			<?php 
			$menu_args = array(
				'container'=> false,
				'fallback_cb' => false,				
				'theme_location' => 'primary'
			); ?>
			<nav>
				<?php wp_nav_menu($menu_args); ?>
			</nav>
		</div> <!-- /#links -->
	</div> 
	
<?php if( !is_front_page() ) { ?>
	</header> 
<?php } ?>