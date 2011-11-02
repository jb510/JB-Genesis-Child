<?php
/**
 * Child Theme Settings
 *
 * This file registers all of this child theme's specific Theme Settings, accessible from
 * Genesis > Child Theme Settings.
 *
 * @package     BE Genesis Child
 * @author      Bill Erickson <bill@billerickson.net>
 * @copyright   Copyright (c) 2011, Bill Erickson
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        https://github.com/billerickson/BE-Genesis-Child
 */ 
 
/**
 * Registers a new admin page, providing content and corresponding menu item
 * for the Child Theme Settings page.
 *
 * @package BE Genesis Child
 * @subpackage Admin
 *
 * @since 1.0.0
 */
class Child_Theme_Settings extends Genesis_Admin_Boxes {
	
	/**
	 * Create an admin menu item and settings page.
	 * 
	 * @since 1.0.0
	 */
	function __construct() {
		
		// Specify a unique page ID. 
		$page_id = 'child';
		
		// Set it as a child to genesis, and define the menu and page titles
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => 'Genesis - Child Theme Settings',
				'menu_title'  => 'Child Theme Settings',
			)
		);
		
		// Set up page options. These are optional, so only uncomment if you want to change the defaults
		$page_ops = array(
		//	'screen_icon'       => 'options-general',
		//	'save_button_text'  => 'Save Settings',
		//	'reset_button_text' => 'Reset Settings',
		//	'save_notice_text'  => 'Settings saved.',
		//	'reset_notice_text' => 'Settings reset.',
		);		
		
		// Give it a unique settings field. 
		// You'll access them from genesis_get_option( 'option_name', 'child-settings' );
		$settings_field = 'child-settings';
		
		// Set the default values
		$default_settings = array(
			'phone'   => '',
			'address' => '',
		);
		
		// Create the Admin Page
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		// Initialize the Sanitization Filter
		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitization_filters' ) );
			
	}

	/** 
	 * Set up Sanitization Filters
	 *
	 * See /lib/classes/sanitization.php for all available filters.
	 *
	 * @since 1.0.0
	 */	
	function sanitization_filters() {
		
		genesis_add_option_filter( 'no_html', $this->settings_field,
			array(
				'phone',
				'address',
			) );
	}
	
	/**
	 * Register metaboxes on Child Theme Settings page
	 *
	 * @since 1.0.0
	 *
	 * @see Child_Theme_Settings::contact_information() Callback for contact information
	 */
	function metaboxes() {
		
		add_meta_box('contact-information', 'Contact Information', array( $this, 'contact_information' ), $this->pagehook, 'main', 'high');
		
	}
	
	/**
	 * Callback for Contact Information metabox
	 *
	 * @since 1.0.0
	 *
	 * @see Child_Theme_Settings::metaboxes()
	 */
	function contact_information() {
		
		echo '<p>Phone:<br />';
		echo '<input type="text" name="' . $this->get_field_name( 'phone' ) . '" id="' . $this->get_field_id( 'phone' ) . '" value="' . esc_attr( genesis_get_option( 'phone', $this->settings_field ) ) . '" size="50" />';
		echo '</p>';
	
		echo '<p>Address</p>';
		echo '<p><textarea name="' . $this->get_field_name( 'address' ) . '" cols="78" rows="8">' . esc_textarea( genesis_get_option( 'address', $this->settings_field ) ) . '</textarea></p>';		
	}
	
	
}


/**
 * Add the Theme Settings Page
 *
 * @since 1.0.0
 */
function be_add_child_theme_settings() {
	global $_child_theme_settings;
	$_child_theme_settings = new Child_Theme_Settings;	 	
}
add_action( 'admin_menu', 'be_add_child_theme_settings', 5 );