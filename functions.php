<?php
/*
* Child Theme Name: OBM-Geneis-Child for Genesis v2.1.2
* Author: Orange Blossom Media
* Url: http://orangeblossommedia.com/
*/

/* --- DEFINE CHILD THEME --- */

// CHILD_DIR defined in Genesis
define( 'CHILD_THEME_NAME', 'obm-genesis-child' );
define( 'CHILD_THEME_URL', 'http://www.obm-genesis-child.com/' );

/* --- THEME SETUP --- */


// Activate the child theme
// All core child theme actions and filters hooked here
add_action('genesis_setup','obm_theme_setup', 15);
function obm_theme_setup() {

	// Sets up child, including scripts and styles
	include_once( CHILD_DIR . '/lib/child_setup.php');

	// Child theme functions
	include_once( CHILD_DIR . '/lib/child_theme_functions.php');

	// Child theme admin area functions
	include_once( CHILD_DIR . '/lib/admin_functions.php');

	// Mobile Detection PHP support
	// https://github.com/serbanghita/Mobile-Detect
	include_once( CHILD_DIR . '/lib/Mobile_Detect.php');
	$detect = new Mobile_Detect;

	// Stop updates from WordPress repo
	add_filter( 'http_request_args', 'obm_dont_update', 5, 2 );


	/* --- THEME SUPPORT --- */

	// Turn On HTML5 Markup @since GENESIS 2.0 final
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Add structural support
	add_theme_support( 'genesis-structural-wraps', array(
		'header',
		'nav',
		'subnav',
		'site-inner',
		'footer-widgets',
		'footer'
	) );


	// Menus
	add_theme_support( 'genesis-menus', array(
		'primary' 	=> 'Primary Navigation Menu',
		'secondary' => 'Footer Navigation Menu',
	) );


	/* --- UNREGISTER LAYOUTS AND WIDGETS --- */

	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
	// genesis_unregister_layout( 'content-sidebar' );
	// genesis_unregister_layout( 'sidebar-content' );
	// genesis_unregister_layout( 'full-width-content' );

	// Remove Genesis Widgets
	add_action( 'widgets_init', 'obm_remove_genesis_widgets', 20 );
	function obm_remove_genesis_widgets() {
		unregister_widget( 'Genesis_eNews_Updates' );
		unregister_widget( 'Genesis_Featured_Page' );
		unregister_widget( 'Genesis_Featured_Post' );
		unregister_widget( 'Genesis_Latest_Tweets_Widget' );
		unregister_widget( 'Genesis_Menu_Pages_Widget' );
		unregister_widget( 'Genesis_User_Profile_Widget' );
		unregister_widget( 'Genesis_Widget_Menu_Categories' );
	}


	/* --- SIDEBARS AND WIDGETS --- */

	// Remove Sidebars
	//unregister_sidebar( 'header-right' );
	// unregister_sidebar( 'sidebar' );
	unregister_sidebar( 'sidebar-alt' );

	// Add footer widgets (second argument is number of widgets)
	add_theme_support( 'genesis-footer-widgets', 2 );

	// Home page widgets
	genesis_register_sidebar( array(
	 	'id'			=> 'home-hero',
	 	'name'			=> __( 'Home Hero', 'obm_theme' ),
	 ) );


	/* --- <HEAD> ELEMENTS --- */

	// Remove default stylesheet
	// See /lib/child_setup.php
	remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	// Enqueue base scripts and styles
	// See /lib/child_setup.php
	add_action( 'wp_enqueue_scripts', 'obm_scripts_and_styles', 999 );

	// Add viewport meta tag for mobile browsers @since GENESIS 2.0
	add_theme_support( 'genesis-responsive-viewport' );

	// Change favicon location
	add_filter( 'genesis_pre_load_favicon', 'obm_custom_favicon_location' );


	/* --- CLEANING <HEAD> --- */

	// Remove rsd link
	remove_action( 'wp_head', 'rsd_link' );
	// Remove Windows Live Writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// Remove index link
	remove_action( 'wp_head', 'index_rel_link' );
	// Remove previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// Remove start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// Remove links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// Remove WP version
	remove_action( 'wp_head', 'wp_generator' );


	/* --- CHILD THEME IMAGE SIZES --- */
	/*
		* To add more sizes, simply copy a line from below and change the dimensions & name.
		  As long as you upload a "featured image" as large as the biggest set width or height,
		  all the other sizes will be auto-cropped.

		* To call a different size, simply change the text inside the thumbnail function.

		* For example, to call the 225 x 225 sized image, we would use the function:
		  <?php the_post_thumbnail( 'obm_medium_img' ); ?>

		* You can change the names and dimensions to whatever you like.
	*/

	add_image_size( 'obm_featured_img', 600, 400, TRUE );


	// Change image to default to no link
	update_option( 'image_default_link_type' , 'none' );


	// Hide media library items uploaded by other users on upload, unless user is admin
	add_filter( 'posts_where', 'obm_hide_media_library' );
	function obm_hide_media_library( $where ){

	    global $current_user;

	    if( is_user_logged_in() && !current_user_can( 'manage_options' ) ){
	         if( isset( $_POST['action'] ) && ( $_POST['action'] == 'query-attachments' ) ){
	            $where .= ' AND post_author='.$current_user->data->ID;
	        }
	    }

	    return $where;

	}


	/* --- HEADER AREA --- */

	// Add support for custom logo header
	add_theme_support( 'custom-header', array(
		'default-color'		=> '000000',
		'header-selector'	=> '.site-title a',
		'header-text'		=> false,
		'flex-height'		=> true,
		'flex-width'		=> true,
		'height'			=> 100,
		'width'				=> 200,
	) );


	// Reposition the navigation
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	add_action( 'genesis_before_header', 'genesis_do_nav' );
	remove_action( 'genesis_after_header', 'genesis_do_subnav' );

	// Remove Header Title and Tagline
	// remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
	// remove_action( 'genesis_site_description', 'genesis_seo_site_description' );


	/* --- FOOTER AREA --- */

	// footer credit & attribution text
	add_filter( 'genesis_footer_creds_text', 'obm_footer_cred' );


	/* --- POSTS --- */

	// Remove Post Meta
	remove_action( 'genesis_before_post_content', 'genesis_post_info', 99 );
	remove_action( 'genesis_before_entry_content', 'genesis_post_info', 99 );

	// Reposition Post Image
	remove_action( 'genesis_post_content', 'genesis_do_post_image' );
	add_action( 'genesis_before_post', 'genesis_do_post_image' );

}
