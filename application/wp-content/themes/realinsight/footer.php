<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage RealInsight
 * @since RealInsight 1.0
 */
?>

	
	<footer>
	
		<div class="container">

			<?php get_sidebar( 'footer' ); ?>

			<p>
				<span itemprop="name">RealINSIGHT Software.</span> 
				<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
					<span itemprop="streetAddress">5215 North O'Connor Blvd, Suite 350</span>, <span itemprop="addressLocality">Irving</span>, <span itemprop="addressRegion">TX</span> <span itemprop="postalCode">75039</span><br />
				</span>
				<span itemprop="telephone"><a href="tel:+18668580785">1-866.858.0785</a></span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Phone: <span itemprop="telephone"><a href="tel:+19728700785">972.870.0785</a></span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span itemprop="terms"><a href="/terms-of-service/">Terms of Service</a></span><br />
				&copy; CFWS Insight LLC.
			</p>

		</div>

	</footer>

	<?php wp_footer(); ?>
</body>
</html>
