<?php
/**
 * Functions
 *
 * @package      BE_Genesis_Child
 * @since        1.0.0
 * @link         https://www.jbrownstudios.com
 * @author       Jon Brown <jon@jbrownstudios.com>
 * @copyright    Copyright (c) 2012, Jon Brown
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @attribution	 Child theme based on the work of Bill Erickson https://github.com/billerickson/BE-Genesis-Child
 */

/**
 * Theme Setup
 * @since 1.0.0
 *
 * This setup function attaches all of the site-wide functions 
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 *
 */

add_action('genesis_setup','child_theme_setup', 15);
function child_theme_setup() {
	
	// ** Backend **	
	
	// Image Sizes
	// add_image_size ('be_featured', 400, 100, true );
	
	// Menus
	add_theme_support( 'genesis-menus', array( 'primary' => 'Primary Navigation Menu' , 'secondary' => 'Secondary Navigation Menu'  ) );
	
	// Sidebars
	unregister_sidebar('sidebar-alt');
	//genesis_register_sidebar(array('name' => 'Blog Sidebar', 'id' => 'blog-sidebar'));
	//add_theme_support( 'genesis-footer-widgets', 3 );

	// Remove Unused Page Layouts
	//genesis_unregister_layout( 'full-width-content' );
	//genesis_unregister_layout( 'content-sidebar' );	
	//genesis_unregister_layout( 'sidebar-content' );
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );
	
	// Remove Unused Theme Settings
	add_action( 'genesis_theme_settings_metaboxes', 'be_remove_metaboxes' );
	
	// Remove Unused User Settings
	add_filter( 'user_contactmethods', 'be_contactmethods' );
	remove_action( 'show_user_profile', 'genesis_user_options_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
	remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );

	// Remove Genesis Widgets
	add_action( 'widgets_init', 'be_remove_genesis_widgets', 20 );
	
	// Editor Styles
	add_editor_style( 'editor-style.css' );
		
	// Setup Theme Settings
	//include_once( CHILD_DIR . '/lib/functions/child-theme-settings.php');
	
	// Don't update theme
	add_filter( 'http_request_args', 'be_dont_update_theme', 5, 2 );
		
	// ** Frontend **		
	
	// Remove Edit link
	add_filter( 'genesis_edit_post_link', '__return_false' );
	
	// Remove Genesis Footer
	remove_action( 'genesis_footer', 'genesis_do_footer' );
}

// ** Backend Functions ** //

/**
 * Remove Metaboxes
 * @since 1.0.0
 *
 * This removes unused or unneeded metaboxes from Genesis > Theme Settings. 
 * See /genesis/lib/admin/theme-settings for all metaboxes.
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/code/remove-metaboxes-from-genesis-theme-settings/
 */
 
function be_remove_metaboxes( $_genesis_theme_settings_pagehook ) {
	remove_meta_box( 'genesis-theme-settings-header', $_genesis_theme_settings_pagehook, 'main' );
	//remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-blogpage', $_genesis_theme_settings_pagehook, 'main' );
}

/**
 * Remove Widgets
 * @since 1.0.?
 *
 * This removes unused or unneeded widgets from Genesis. 
 *
 */
function be_remove_genesis_widgets() {
	//unregister_widget( 'Genesis_Featured_Post' );
	//unregister_widget( 'Genesis_Featured_Page' );
    unregister_widget( 'Genesis_eNews_Updates' );
    unregister_widget( 'Genesis_User_Profile_Widget' );
    unregister_widget( 'Genesis_Menu_Pages_Widget' );
    unregister_widget( 'Genesis_Widget_Menu_Categories' );
    unregister_widget( 'Genesis_Latest_Tweets_Widget' );
}



/**
 * Customize Contact Methods
 * @since 1.0.0
 *
 * @author Bill Erickson
 * @link http://sillybean.net/2010/01/creating-a-user-directory-part-1-changing-user-contact-fields/
 *
 * @param array $contactmethods
 * @return array
 */
function be_contactmethods( $contactmethods ) {
	unset( $contactmethods['aim'] );
	unset( $contactmethods['yim'] );
	unset( $contactmethods['jabber'] );
	
	return $contactmethods;
}

/**
 * Don't Update Theme
 * @since 1.0.0
 *
 * If there is a theme in the repo with the same name, 
 * this prevents WP from prompting an update.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array $r, request arguments
 * @param string $url, request url
 * @return array request arguments
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