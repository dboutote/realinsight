<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage RealInsight
 * @since RealInsight 1.0
 */

get_header(); ?>

<div class="title-bar">
<div class="container clearfix">
<h1>Current Page Title</h1>
<ul id="breadcrumbs">
<li><a href="#">Home</a></li>
<li><a href="#">Current Page Title</a></li>
</ul>
</div>
</div>

<div class="content">
	<div class="container clearfix">
		<h2>This is the page's sub-header field [post-meta field]</h2>
		[page content] <br />
		[pre-footer meta box]	

	</div> <!-- /.container -->
</div> <!-- /.content -->



<?php get_footer(); ?>
