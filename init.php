<?php
/**
 * Product Feature Class
*/
include( 'class.quantity-discounts.php' );

/**
 * Hooks
*/
include( 'addon-hooks.php' );

/**
 * Enqueue admin JS
 *
*/
function it_exchange_quantity_discounts_enqueue_admin_scripts() {
	$screen = get_current_screen();
	if ( empty( $screen->id ) || 'it_exchange_prod' != $screen->id )
		return;

	wp_enqueue_script( 'it-exchange-quantity-discounts-add-edit-product', ITUtility::get_url_from_file( dirname( __FILE__ ) . '/assets/add-edit-product.js' ), array( 'jquery' ) );
	wp_enqueue_style( 'it-exchange-quantity-discounts-add-edit-product', ITUtility::get_url_from_file( dirname( __FILE__ ) . '/assets/add-edit-product.css' ) );
}
add_action( 'admin_enqueue_scripts', 'it_exchange_quantity_discounts_enqueue_admin_scripts' );

/**
 * Exchange will build your add-on's settings page for you and link to it from our add-on
 * screen. You are free to link from it elsewhere as well if you'd like... or to not use our API
 * at all. This file has all the functions related to registering the page, printing the form, and saving
 * the options. This includes the wizard settings. Additionally, we use the Exchange storage API to
 * save / retreive options. Add-ons are not required to do this.
*/
include( 'lib/addon-settings.php' );
