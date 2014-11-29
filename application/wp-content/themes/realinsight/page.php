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

<?php while ( have_posts() ) : the_post(); ?>

	<div class="title-bar">

		<div class="container clearfix">

			<?php the_title( '<h1>', '</h1>'); ?>

			<ul id="breadcrumbs">
				<li>[breadcrumbs]</li>				
			</ul>

		</div>

	</div> <!-- /.title-bar -->

	<div class="content">

		<div class="container clearfix">

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <!-- <?php echo basename(__FILE__); ?> -->
			
				<?php if( has_subheader() ) { ?>
					<h2><?php display_subheader(); ?></h2>
				<?php }; ?>

				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					) );

					edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
					?>
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

<?php endwhile; ?>

<?php get_footer();