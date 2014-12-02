<?php
/**
 * No direct access
 */
defined( 'ABSPATH' ) or die( 'Nothing here!' );

class CPT_Slider
{
	private $meta_config_args;
	const POST_TYPE = 'cpt_slider';


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
		add_action( 'init', array($this, 'register_post_type'), 0 );
		add_action( 'save_post_'.self::POST_TYPE, array($this,'save_meta'), 0, 3 );
		add_filter( 'include_prefoot_dont_show_list', array($this, 'check_post_type'), 0,2);
		add_filter( 'include_subheader_dont_show_list', array($this, 'check_post_type'), 0,2);
		add_filter( 'upload_mimes', array($this, 'cc_mime_types') );
		add_action('admin_head', array($this,'fix_svg_css'));

		//add_filter( 'the_content',  array($this,'process_shortcodes'), 7 );
		add_shortcode( 'ri_slider', array($this, 'ri_slider') );
	}


	/**
	 * Fix displaying an SVG file in the Media Library
	 *
	 * @access public
	 * @since 1.0
	 *
	 */
	function fix_svg_css() {
		echo '<style type="text/css">
			td.media-icon img[src$=".svg"],
			img[src$=".svg"].attachment-post-thumbnail{
				width: 100% !important; height: auto !important;
			}
			</style>
		';
	}


	/**
	 * Allow svg file types to be uploaded
	 *
	 * @access public
	 * @since 1.0
	 *
	 */
	function cc_mime_types( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}


	/**
	 * Remove this post type from the Pre Footer meta box
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @param array $dont_show The array of post types to exclude
	 * @param string $post_type The post type to check against the $dont_show array
	 */
	public function check_post_type($dont_show, $post_type)
	{
		$dont_show[] = self::POST_TYPE;
		return $dont_show;
	}


	/**
	 * Preprocess shortcodes before WordPress processes the content
	 *
	 * @access  public
	 * @since   1.0
	 * @uses do_shortcode()
	 */
	function process_shortcodes($content)
	{
		global $shortcode_tags;

		$orig_shortcode_tags = $shortcode_tags;
		$shortcode_tags      = array();

		add_shortcode( 'ri_slider', array($this, 'ri_slider') );

		$content = do_shortcode($content);

		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;

		return $content;
	}



	/**
	 * Process the ri_slider Shortcode
	 *
	 * @access public
	 * @since 1.0
	 * @param array $atts
	 * @param string $content
	 * @uses shortcode_atts()
	 * @return string $output HTML
	 */
	public function ri_slider($atts, $content = null, $code = '')
	{
		extract(shortcode_atts(array(
			'id' => 0
		), $atts));

		if( (int)$id < 1 ) {
			return;
		}

		// get the post object for this content type
		$post_object = get_post($id);

		if( !is_null($post_object) ){
		
			$slider_title = '<h2>'. apply_filters('the_content',$post_object->post_content) . '</h2>';
		
			// get the children
			$child_args = array(
				'post_parent' => $id,
				'orderby'     => 'menu_order',
				'order'       => 'asc',
				'post_type'   => self::POST_TYPE
				
			);
			$kids = get_posts($child_args);
			
			// for each child, get post meta
			if( $kids ) {
				$nav = '<div class="features-menu">';
				$features = '<ul id="features">';
				foreach($kids as $kid){
					
					$meta = get_post_custom($kid->ID);
					$_icon_nav_title = ( isset($meta['_icon_nav_title'][0]) ) ? $meta['_icon_nav_title'][0] : $kid->post_title ;
					$_icon_nav_url = ( isset($meta['_icon_nav_url'][0]) ) ? $meta['_icon_nav_url'][0] : '' ;
					$_thumbnail_id = ( isset($meta['_thumbnail_id'][0]) ) ? $meta['_thumbnail_id'][0] : '' ;
					if( '' !== $_thumbnail_id  ){
						$image_obj = wp_get_attachment_image_src( $_thumbnail_id, 'full');
							$feat_src = $image_obj[0];
							$feat_width = $image_obj[1];
							$feat_height = $image_obj[2]; 
					}
					
					$nav .= '<div class="option">';
					$nav .= '<img src="'.$_icon_nav_url.'" />';
					$nav .= '<p><strong>'.$_icon_nav_title.'</strong></p>';	
					$nav .= '</div>';
				
					
					
					$features .= '<li>';
					$features .= '<div class="right">';
					if( $image_obj ){
						$features .= '<img src="'.$feat_src.'" width="'.$feat_width.'" height="'.$feat_height.'" alt="" />';
					}
					$features .= '</div>';
					$features .= '<div class="left">';
					$features .= '<blockquote>';
					$features .= '<h3>'.$kid->post_title.'</h3>';
					$features .= apply_filters('the_content',$kid->post_content);
					$features .= '</blockquote>';
					$features .= '</div>';
					$features .= '</li>';
					
				
				}
				$features .= '</ul>';
				$nav .= '</div>';
				
			}
			
			// build slider
			$content = $slider_title . $nav . $features;
			
			wp_reset_postdata();
			
			return $content;
		}

		return $content;

	}


	/**
	 * Process the np_slide Shortcode
	 *
	 * @access public
	 * @since 1.0
	 * @param array $atts
	 * @param string $content
	 * @uses shortcode_atts()
	 * @return string $output HTML
	 */
	public function np_slide($atts, $content = null, $code = '')
	{
		extract(shortcode_atts(array(
			'id' => '',
			'classes' => ''
		), $atts));

		$classes = ( '' !== $classes  ) ? ' class="'.$classes.'"' : '';

		$id = ( '' !== $id  ) ? ' id="'.$id.'"' : '';

		return '<div '.$id.$classes.'>'.do_shortcode($content).'</div>';
	}


	/**
	 * Register post type
	 */
	public static function register_post_type()
	{
		$name = 'Slider';
		$plural     = 'Sliders';

		// Labels
		$labels = array(
			'name'                 => _x( $plural, 'post type general name' ),
			'singular_name'        => _x( $name, 'post type singular name' ),
			'add_new'              => _x( 'Add New', strtolower( $name ) ),
			'menu_name'            => __( 'Slider Codes' ),
			'add_new_item'         => __( 'Add New ' . $name ),
			'edit_item'            => __( 'Edit ' . $name ),
			'new_item'             => __( 'New ' . $name ),
			'all_items'            => __( 'All ' . $plural ),
			'view_item'            => __( 'View ' . $name ),
			'search_items'         => __( 'Search ' . $plural ),
			'not_found'            => __( 'No ' . strtolower( $plural ) . ' found'),
			'not_found_in_trash'   => __( 'No ' . strtolower( $plural ) . ' found in Trash'),
		);

		// Register post type
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'                 => $labels,
				'public'                 => false,
				'exclude_from_search'    => true,
				'show_in_nav_menus'      => false,
				'show_ui'                => true,
				'menu_position'          => 5,
				'menu_icon'              => 'dashicons-format-gallery',
				'hierarchical'           => true,
				'capability_type'        => 'page',
				'supports'               => array('title', 'editor','page-attributes', 'thumbnail'),
				'register_meta_box_cb'   => array(__CLASS__, 'create_metabox' ),
				'taxonomies'             => array(),
				'has_archive'            => false,
				'rewrite'                => false,
				'query_var'              => false
			)
		);
	}


	/**
	 * Create the metabox
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @uses add_meta_box()
	 */
	public static function create_metabox()
	{

		$args = self::get_meta_box_args();
		extract($args);

		if ( function_exists('add_meta_box') ) {
			foreach ($content_types as $content_type) {
				add_meta_box($meta_box_id, $meta_box_title, array(__CLASS__, 'inner_metabox'), $content_type, $meta_box_position );
			}
		}
	}


	/**
	 * Configuration params for the Metabox
	 *
	 * @since 1.0
	 * @access protected
	 *
	 */
	protected static function get_meta_box_args()
	{
		return self::set_meta_box_args();
	}


	/**
	 * Configuration params for the Metabox
	 *
	 * @access protected
	 * @since 1.0
	 *
	 */
	protected static function set_meta_box_args()
	{
		$basename = 'galleryinfo';
		$post_type = get_post_type();
		$post_types = array(self::POST_TYPE);

		if( $post_type ){
			$post_type_name = strtolower( get_post_type_object( $post_type )->labels->singular_name );
		}

		$meta_fields = array(
			'icon_nav_title' => array(
				'name' => 'icon_nav_title',
				'type' => 'text',
				'default' => '',
				'title' => __('Icon Nav Title'),
				'description' => sprintf( __( 'Enter an optional editorial post title. Default: %s title.', 'rinsight' ), $post_type_name )
			),
			'icon_nav_url' => array(
				'name' => 'icon_nav_url',
				'type' => 'text',
				'default' => '',
				'title' => __('Icon Nav URL'),
				'description' => __( 'Enter an optional icon url. Default: none.', 'rinsight' )
			)
		);

		$description = '';
		$description .= 'When creating a slider, you need to first create a "parent" slide. This parent slide will act as a wrapper for all the slides needed by this slider.';
		
		$args = array(
			'meta_box_id' => $basename . 'div',
			'meta_box_name' => $basename . 'info',
			'meta_box_title' => __( 'Slider Shortcode Overview', 'rinsight' ),
			'meta_box_default' => '',
			'meta_box_description' => $description,
			'content_types' => $post_types,
			'meta_box_position' => 'advanced',
			'meta_box_priority' => 'high',
			'meta_fields' => $meta_fields
		);

		return $args;
	}


	/**
	 * Print the inner HTML of the metabox
	 *
	 * @access public
	 * @since 1.0
	 *
	 */
	public static function inner_metabox()
	{

		global $post;
		global $pagenow;

		// get configuration args
		$args = self::get_meta_box_args();
		extract($args);

		$postID = ( $post && $post->ID > 0 ) ? $post->ID : 0 ;
		$postParent = ( $post && $post->post_parent > 0 ) ? $post->post_parent : 0 ;

		if('post-new.php' === $pagenow){
			$postID = 0;
		}

		
		$output = '';
		$output .= '<p>'.$meta_box_description.'</p>';


		if( $postID > 0 && $postParent < 1 ){
			$output .=  '<p><b>Copy/paste this shortcode wherever you want your slider to appear.</b><br /><input type="text" style="width:98%;" value="[ri_slider id='.$postID.']" readonly="readonly" /></p>';
		}

		$output .= ' <hr /><h4>The following settings are only used if you are creating a child slide.</h4>';

		foreach( $meta_fields as $meta_field ) {

			$meta_field_value = get_post_meta($post->ID, '_'.$meta_field['name'], true);

			if( '' === $meta_field_value ) {
				$meta_field_value = $meta_field['default'];
			}

			wp_nonce_field( plugin_basename(__CLASS__), $meta_field['name'].'_noncename' );

			if ( 'icon_nav_title' === $meta_field['name']) {
				$output .= '<p><b><label for="'.$meta_field['name'].'">'.$meta_field['title'].'</label></b><br />';
				$output .= '<input class="reg-text" type="text" id="'.$meta_field['name'].'" name="'.$meta_field['name'].'" value="'.$meta_field_value.'" size="16" style="width: 99%;" /> <span class="desc">'.$meta_field['description'].'</span></p>';
			}

			if ( 'icon_nav_url' === $meta_field['name']) {
				$output .= '<p><b><label for="'.$meta_field['name'].'">'.$meta_field['title'].'</label></b><br />';
				$output .= '<input class="reg-text" type="text" id="'.$meta_field['name'].'" name="'.$meta_field['name'].'" value="'.$meta_field_value.'" size="16" style="width: 99%;" /> <span class="desc">'.$meta_field['description'].'</span></p>';
			}

		}

		echo $output;

		return;

	}


	/**
	 * Process saving the metadata
	 *
	 * @access public
	 * @since 1.0
	 *
	 */
	 public function save_meta($post_id, $post, $update)
	 {
		// if there's no $post object it's a new post
		if( !$post && $post_id > 0 ) {
			$post = get_post($post_id);
		}

		if(!$post) {
			return $post_id;
		}

		if( 'auto-draft' === $post->post_status ){
			return $post_id;
		}

		// skip auto-running jobs
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
		if ( defined('DOING_AJAX') && DOING_AJAX ) return;
		if ( defined('DOING_CRON') && DOING_CRON ) return;

		// Don't save if the post is only an auto-revision.
		if ( 'revision' == $post->post_type ) {
			return $post_id;
		}

		// Get the post type object & check if the current user has permission to edit the entry.
		$post_type = get_post_type_object( $post->post_type );

		if ( $post_type && !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		// get configuration args
		$args = self::get_meta_box_args();
		extract($args);

		foreach($meta_fields as $meta_field) {

			// verify this came from the our screen and with proper authorization, (b/c save_post can be triggered at other times)
			if( !isset($_POST[$meta_field['name'].'_noncename']) || !wp_verify_nonce( $_POST[$meta_field['name'].'_noncename'], __CLASS__ ) ) {
				return $post_id;
			}

			// Ok, we're authenticated: we need to find and save the data
			$data = ( isset($_POST[$meta_field['name']]) ) ? $_POST[$meta_field['name']] : '';
			$data = ( is_array($data) ) ? array_filter($data) : trim($data);

			if ( '' != $data && '-1' != $data  ) {
				update_post_meta( $post->ID, '_'.$meta_field['name'], $data );
			} else {
				delete_post_meta( $post->ID, '_'.$meta_field['name'] );
			}

		}

		return $post_id;

	 }

}


$CPT_Slider = new CPT_Slider();