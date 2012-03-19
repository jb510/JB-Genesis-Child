<?php
/*
Template Name: Portfolio
*/

/** Force the full width layout layout on the Portfolio page */
add_filter( 'genesis_pre_get_option_site_layout', 'jb_portfolio_layout' );
function jb_portfolio_layout( $opt ) {
	return 'full-width-content';
}

/** Remove the standard loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );

/** Add the Portfolio widget area */
add_action( 'genesis_loop', 'jb_porfolio_widget' );
function jb_porfolio_widget() {
	dynamic_sidebar( 'portfolio' );
}

genesis();