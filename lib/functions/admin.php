<?php
/**
 * Theme Settings
 *
 * This file registers all of this child theme's specific Theme Settings, accessible from
 * Genesis > Theme Settings.
 *
 * @package		BE Genesis Child
 * @author		Bill Erickson <bill@billerickson.net>
 * @copyright	Copyright (c) 2011, Bill Erickson
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */ 

add_filter('genesis_theme_settings_defaults', 'be_theme_defaults');

/**
 * Register Defaults
 *
 * @param array $defaults
 * @return array modified defaults
 *
 */

function be_theme_defaults($defaults) {
	$defaults[] = array(
		'feature_title' => '',
		'feature_subtitle' => '',
		'footer_disclaimer' => ''
	);

	return $defaults;
}

add_action( 'genesis_settings_sanitizer_init', 'be_register_theme_sanitization_filters' );

/**
 * Sanitization
 *
 */

function be_register_theme_sanitization_filters() {
	
	genesis_add_option_filter( 'no_html', GENESIS_SETTINGS_FIELD,
	array(
		'feature_title',
		'feature_subtitle',
	) );
	
	genesis_add_option_filter('safe_html', GENESIS_SETTINGS_FIELD,
	array(
		'footer_disclaimer'
	));

}

add_action('genesis_theme_settings_metaboxes', 'be_register_theme_settings_box');

/**
 * Register Metaboxes
 *
 */

function be_register_theme_settings_box() {
	global $_genesis_theme_settings_pagehook;

	add_meta_box('be-feature-settings', 'Feature Box', 'be_feature_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high');
	add_meta_box('be-disclaimer-settings', 'Footer Disclaimer', 'be_disclaimer_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high');
} 

/**
 * Create Metaboxes
 *
 */

function be_feature_settings_box() {
?>

	<p>Title:<br />
	<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[feature_title]" value="<?php echo esc_attr( genesis_get_option('feature_title') ); ?>" size="50" /> </p>

	<p>Subtitle:<br />
	<input type="text" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[feature_subtitle]" value="<?php echo esc_attr( genesis_get_option('feature_subtitle') ); ?>" size="50" /> </p> 

<?php
}

function be_disclaimer_settings_box() {
?>

	<p>Footer Disclaimer</p>

	<textarea name="<?php echo GENESIS_SETTINGS_FIELD; ?>[footer_disclaimer]" cols="78" rows="8"><?php echo esc_textarea( genesis_get_option('footer_disclaimer') ); ?></textarea>
	
<?php
}