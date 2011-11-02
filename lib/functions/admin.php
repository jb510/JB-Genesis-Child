<?php
/**
 * Child Theme Settings
 *
 * This file registers all of this child theme's specific Theme Settings, accessible from
 * Genesis > Child Theme Settings.
 *
 * @package		BE Genesis Child
 * @author		Bill Erickson <bill@billerickson.net>
 * @copyright	Copyright (c) 2011, Bill Erickson
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */ 
 
// Create a new class for each admin page you'd like 
class Child_Admin_Settings extends Genesis_Admin_Boxes {
	
	function __construct() {
		
		// Specify a unique page ID. 
		$page_id = 'child';
		
		// Set it as a child to genesis, and define the menu and page titles
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => __( 'Genesis - Child Theme Settings', 'genesis' ),
				'menu_title'  => __( 'Child Theme Settings', 'genesis' )
			)
		);
		
		$page_ops = array(
			'screen_icon'       => 'options-general',
			'save_button_text'  => __( 'Save Settings', 'genesis' ),
			'reset_button_text' => __( 'Reset Settings', 'genesis' ),
			'saved_notice_text' => __( 'Settings saved.', 'genesis' ),
			'reset_notice_text' => __( 'Settings reset.', 'genesis' )
		);
		
		// Give it a unique settings field. 
		// You'll access them from genesis_get_option( 'option_name', CHILD_SETTINGS_FIELD );
		$settings_field = CHILD_SETTINGS_FIELD;
		
		// Set the default values
		$default_settings = apply_filters( 'child_theme_settings_defaults', array(
			'feature_title' => '',
			'feature_subtitle' => '',
			'footer_disclaimer' => ''
		) );
		
		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );
		
		/** for backward compatibility */
		global $_genesis_theme_settings_pagehook;
		$_genesis_theme_settings_pagehook = $this->pagehook;
		
	}
	
	// Create the metaboxes
	function metaboxes() {
		
		add_meta_box('be-feature-settings', 'Feature Box', array( $this, 'be_feature_settings_box' ), $this->pagehook, 'main', 'high');
		add_meta_box('be-disclaimer-settings', 'Footer Disclaimer', array( $this, 'be_disclaimer_settings_box' ), $this->pagehook, 'main', 'high');
		
	}
	
	/**
	 * Callback for Feature Box
	 *
	 */
	function be_feature_settings_box() {
	?>
	
		<p>Title:<br />
		<input type="text" name="<?php echo CHILD_SETTINGS_FIELD; ?>[feature_title]" value="<?php echo esc_attr( genesis_get_option( 'feature_title', CHILD_SETTINGS_FIELD ) ); ?>" size="50" /> </p>
	
		<p>Subtitle:<br />
		<input type="text" name="<?php echo CHILD_SETTINGS_FIELD; ?>[feature_subtitle]" value="<?php echo esc_attr( genesis_get_option( 'feature_subtitle', CHILD_SETTINGS_FIELD ) ); ?>" size="50" /> </p> 
	
	<?php
	}
	
	/**
	 * Callback for Footer Disclaimer
	 *
	 */
	function be_disclaimer_settings_box() {
	?>
	
		<p>Footer Disclaimer</p>
	
		<textarea name="<?php echo CHILD_SETTINGS_FIELD; ?>[footer_disclaimer]" cols="78" rows="8"><?php echo esc_textarea( genesis_get_option( 'footer_disclaimer', CHILD_SETTINGS_FIELD ) ); ?></textarea>
		
	<?php
	}
}

// Make sure this matches the class you defined at the top
new Child_Admin_Settings;


// Set up sanitization filters. Make sure you use correct settings field ( CHILD_SETTINGS_FIELD ).
// Options: one_zero, no_html, safe_html, requires_unfiltered_html
// You can also create your own with `genesis_available_sanitizer_filters` filter. See /lib/classes/sanitization
function be_register_theme_sanitization_filters() {
	
	genesis_add_option_filter( 'no_html', CHILD_SETTINGS_FIELD,
	array(
		'feature_title',
		'feature_subtitle',
	) );
	
	genesis_add_option_filter('safe_html', CHILD_SETTINGS_FIELD,
	array(
		'footer_disclaimer'
	));
}
add_action( 'genesis_settings_sanitizer_init', 'be_register_theme_sanitization_filters' );


