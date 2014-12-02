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

	<div class="hero container clearfix">
		<div class="right"><img src="<?php echo get_stylesheet_directory_uri();?>/images/blank.gif" class="lc-overlay" alt="" width="350" height="350" usemap="#Map">
			<map name="Map">
				<area shape="poly" coords="206,91,255,61,223,7,294,42,331,97,309,153,253,138,234,108" id="lc-loan">
				<area shape="poly" coords="260,148,312,169,336,112,351,156,346,210,335,249,276,267,253,213,263,185" id="lc-surveillance">
				<area shape="poly" coords="247,223,266,278,329,260,277,320,218,346,168,309,192,261,226,248" id="lc-watchlist">
				<area shape="poly" coords="182,262,144,259,120,244,66,250,69,317,120,342,202,351,154,312" id="lc-transfer">
				<area shape="poly" coords="111,235,94,209,87,179,46,136,-2,181,16,252,34,278,58,307,54,242" id="lc-liquidation">
				<area shape="poly" coords="89,163,100,130,119,109,125,50,63,40,26,85,6,125,-1,164,47,123" id="lc-reo">
				<area shape="poly" coords="132,99,160,88,193,89,239,57,205,1,127,5,77,30,134,41" id="lc-sold">
			</map>
			<div class="lc-container"></div>
		</div>
		<div class="left">
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> <!-- <?php echo basename(__FILE__); ?> -->

					<?php if( has_subheader() ) { ?>
						<h2><?php display_subheader(); ?></h2>
					<?php }; ?>

					<div class="entry-content">
						<?php the_content(); ?>
						<?php edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-content -->

				</article><!-- #post-## -->

			<?php endwhile; ?>
		</div>
	</div>

</header>

<div class="content">
	<div class="container">
		<?php
			$ri_slider = get_post_meta(get_the_ID(), 'ri_slider', true);
			if( '' !== $ri_slider ){
				echo do_shortcode($ri_slider);
				//echo apply_filters('the_content', $ri_slider);
			}
		?>
	</div>
</div> <!-- /.content -->

<div class="clients-section">
	<div class="container">
		<?php $page = get_page_by_title( 'Clients Section' ); ?>
		<?php if($page) : ?>
			<?php if( has_subheader($page->ID) ) { ?>
				<h2><?php display_subheader($page->ID); ?></h2>
			<?php }; ?>
			<?php echo apply_filters('the_content', $page->post_content);?>
		<?php endif; ?>
	</div>
</div> <!-- /.clients-section -->


<?php get_footer();