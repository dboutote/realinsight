<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage RealInsight
 * @since RealInsight 1.0
 */

get_header(); ?>



	<div class="title-bar">

		<div class="container clearfix">

			<h1>(404) Not Found</h1>

			<?php if ( function_exists( 'breadcrumb_trail' ) ) { ?>
				<div id="breadcrumbs">
					<?php breadcrumb_trail(array( 'show_browse' => false, 'separator'=>'&middot;' )); ?>
				</div>
			<?php } ?>

		</div>

	</div> <!-- /.title-bar -->

	<div class="content">

		<div class="container clearfix">

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <!-- <?php echo basename(__FILE__); ?> -->
			
				<div class="entry-content">
					<?php _e('We could not find the content your were looking for.');?>
				</div><!-- .entry-content -->

			</article><!-- #post-## -->

		</div> <!-- /.container -->

	</div> <!-- /.content -->
	
	<?php if( has_prefooter_content() ) { 

		$pfclasses = array('pre-footer');
		$has_accordion_content = false;

		if( has_prefooter_accordion_content() ){
			$has_accordion_content = true;
			$pfclasses[] = 'accordion';
		}
		$pfclasses_str = implode($pfclasses, ' '); ?>

		<div class="<?php echo $pfclasses_str;?>">
			<div class="container clearfix">
				<?php display_prefooter_content(); ?>
			</div>
		</div>

		<?php if( $has_accordion_content ) { ?>
		
			<div class="accordion-content">
				<div class="container clearfix">
					<?php display_prefooter_accordion_content(); ?>
				</div>
			</div>
		
		<?php } 
	}; ?>



<?php get_footer();