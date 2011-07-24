<?php
/**
 * Functions
 *
 * @package      BE Genesis Child
 * @author       Bill Erickson <bill@billerickson.net>
 * @copyright    Copyright (c) 2011, Bill Erickson
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

/**
 * Theme Setup
 *
 * This setup function attaches all of the site-wide functions 
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 *
 */

add_action('genesis_setup','child_theme_setup', 15);
function child_theme_setup() {
	
	// ** Backend **
	// Remove Unused Menu Items
	add_action('admin_menu', 'be_remove_menus');
	
	// Customize Menu Order
	add_filter( 'custom_menu_order', 'be_custom_menu_order' );
	add_filter( 'menu_order', 'be_custom_menu_order' );
	
	// Set up Post Types
	//add_action( 'init', 'be_create_my_post_types' );	
	
	// Set up Taxonomies
	//add_action( 'init', 'be_create_my_taxonomies' );

	// Set up Meta Boxes
	//add_action( 'init' , 'be_create_metaboxes' );

	// Setup Sidebars
	//unregister_sidebar('sidebar-alt');
	//genesis_register_sidebar(array('name' => 'Blog Sidebar', 'id' => 'blog-sidebar'));
	//add_theme_support( 'genesis-footer-widgets', 3 );

	// Remove Unused Page Layouts
	//genesis_unregister_layout( 'full-width-content' );
	//genesis_unregister_layout( 'content-sidebar' );	
	//genesis_unregister_layout( 'sidebar-content' );
	//genesis_unregister_layout( 'content-sidebar-sidebar' );
	//genesis_unregister_layout( 'sidebar-sidebar-content' );
	//genesis_unregister_layout( 'sidebar-content-sidebar' );
		
	// Setup Shortcodes
	include_once( CHILD_DIR . '/lib/functions/shortcodes.php');
	
	// Setup Widgets
	//include_once( CHILD_DIR . '/lib/widgets/widget-social.php');
	
	// Don't update theme
	add_filter( 'http_request_args', 'be_dont_update_theme', 5, 2 );
		
	// ** Frontend **		
	// Remove Edit link
	add_filter( 'edit_post_link', '__return_false' );
}

// ** Backend Functions ** //

/**
 * Remove Menu Items
 *
 * Remove unused menu items by adding them to the array.
 * See the commented list of menu items for reference.
 *
 */

function be_remove_menus () {
	global $menu;
	$restricted = array(__('Links'));
	// Example:
	//$restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}

/**
 * Customize Menu Order
 *
 * @param $menu_ord. Current order.
 * @return $menu_ord. New order.
 *
 */

function be_custom_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;
	return array(
		'index.php', // this represents the dashboard link
		'edit.php?post_type=page', //the page tab
		'edit.php', //the posts tab
		'edit-comments.php', // the comments tab
		'upload.php', // the media manager
    );
}

/**
 * Create Post Types
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 *
 */

function be_create_my_post_types() {
	$labels = array(
		'name' => 'Rotator Items',
		'singular_name' => 'Rotator Item',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Rotator Item',
		'edit_item' => 'Edit Rotator Item',
		'new_item' => 'New Rotator Item',
		'view_item' => 'View Rotator Item',
		'search_items' => 'Search Rotator Items',
		'not_found' =>  'No rotator items found',
		'not_found_in_trash' => 'No rotator items found in trash',
		'parent_item_colon' => '',
		'menu_name' => 'Rotator'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','thumbnail','excerpt')
	); 

	register_post_type( 'rotator', $args );
}

/**
 * Create Taxonomies
 *
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 *
 */

function be_create_my_taxonomies() {
	$labels = array(
		'name' => 'Locations',
		'singular_name' => 'Location',
		'search_items' =>  'Search Locations',
		'all_items' => 'All Locations',
		'parent_item' => 'Parent Location',
		'parent_item_colon' => 'Parent Location:',
		'edit_item' => 'Edit Location',
		'update_item' => 'Update Location',
		'add_new_item' => 'Add New Location',
		'new_item_name' => 'New Location Name',
		'menu_name' => 'Location'
	); 	

	register_taxonomy( 'rotator-location', array('rotator'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'rotator-location' ),
	));}

/**
 * Create Metaboxes
 *
 * @link http://www.billerickson.net/wordpress-metaboxes/
 *
 */

function be_create_metaboxes() {
	$prefix = 'be_';
	$meta_boxes = array();

	$meta_boxes[] = array(
    	'id' => 'rotator-options',
	    'title' => 'Rotator Options',
	    'pages' => array('rotator'), // post type
		'context' => 'normal',
		'priority' => 'low',
		'show_names' => true, // Show field names left of input
		'fields' => array(
			array(
				'name' => 'Instructions',
				'desc' => 'In the right column upload a featured image. Make sure this image is at least 900x360px wide. Then fill out the information below.',
				'type' => 'title',
			),
			array(
		        'name' => 'Display Info',
		        'desc' => 'Show Title and Excerpt from above',
	    	    'id' => 'show_info',
	        	'type' => 'checkbox'
			),
			array(
				'name' => 'Clickthrough URL', 
	            'desc' => 'Where the Learn More button goes',
            	'id' => 'url',
            	'type' => 'text'
			),
		),
	);
 	
 	require_once(CHILD_DIR . '/lib/metabox/init.php'); 
}


/**
 * Don't Update Theme
 * If there is a theme in the repo with the same name, 
 * this prevents WP from prompting an update.
 *
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 */

function be_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}

// ** Frontend Functions ** //


// ** Helper Functions ** //
