<?php
/**
 * Product Feature Class
*/
include( 'class.quantity-discounts.php' );

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

