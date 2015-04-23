<?php
/*
 * Plugin Name: iThemes Exchange - Quantity Discounts
 * Version: 1.0.4
 * Description: Allows store owners the ability to set bulk discounts for products.
 * Plugin URI: http://ithemes.com/exchange/quantity-discounts/
 * Author: iThemes
 * Author URI: http://ithemes.com
 * iThemes Package: exchange-addon-quantity-discounts
 
 * Installation:
 * 1. Download and unzip the latest release zip file.
 * 2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 * 3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 * 4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 *
*/

/**
 * This registers our plugin as an exchange addon
 *
 * To learn how to create your own-addon, visit http://ithemes.com/codex/page/Exchange_Custom_Add-ons:_Overview
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_register_quantity_discounts_addon() {
	$options = array(
		'name'              => __( 'Quantity Discounts', 'LION' ),
		'description'       => __( 'Allows store owners the ability to set bulk discounts for products.', 'LION' ),
		'author'            => 'iThemes',
		'author_url'        => 'http://ithemes.com/exchange/quantity-discounts/',
		'icon'              => ITUtility::get_url_from_file( dirname( __FILE__ ) . '/quantity-discounts50px.png' ),
		'file'              => dirname( __FILE__ ) . '/init.php',
		'category'          => 'product-features',
	);
	it_exchange_register_addon( 'quantity-discounts', $options );
}
add_action( 'it_exchange_register_addons', 'it_exchange_register_quantity_discounts_addon' );

/**
 * Loads the translation data for WordPress
 *
 * @uses load_plugin_textdomain()
 * @since 1.0.0
 * @return void
*/
function it_exchange_quantity_discounts_set_textdomain() {
	load_plugin_textdomain( 'LION', false, dirname( plugin_basename( __FILE__  ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'it_exchange_quantity_discounts_set_textdomain' );

/**
 * Registers Plugin with iThemes updater class
 *
 * @since 1.0.0
 *
 * @param object $updater ithemes updater object
 * @return void
*/
function ithemes_exchange_addon_quantity_discounts_updater_register( $updater ) { 
	    $updater->register( 'exchange-addon-quantity-discounts', __FILE__ );
}
add_action( 'ithemes_updater_register', 'ithemes_exchange_addon_quantity_discounts_updater_register' );
require( dirname( __FILE__ ) . '/lib/updater/load.php' );
