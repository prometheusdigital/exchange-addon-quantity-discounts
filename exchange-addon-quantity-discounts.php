<?php
/*
 * Plugin Name: ExchangeWP - Quantity Discounts
 * Version: 0.0.1
 * Description: Allows store owners the ability to set bulk discounts for products.
 * Plugin URI: https://exchangewp.com/downloads/quantity-discounts/
 * Author: ExchangeWP
 * Author URI: https://exchangwp.com
 * ExchangeWP Package: exchange-addon-quantity-discounts

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
 * To learn how to create your own-addon, visit https://support.exchangewp.com
 *
 * @since 1.0.0
 *
 * @return void
*/
function it_exchange_register_quantity_discounts_addon() {
	$options = array(
		'name'              => __( 'Quantity Discounts', 'LION' ),
		'description'       => __( 'Allows store owners the ability to set bulk discounts for products.', 'LION' ),
		'author'            => 'ExchangeWP',
		'author_url'        => 'https://exchangewp.com/downloads/quantity-discounts/',
		'icon'              => ITUtility::get_url_from_file( dirname( __FILE__ ) . '/quantity-discounts50px.png' ),
		'file'              => dirname( __FILE__ ) . '/init.php',
		'category'          => 'product-features',
		'settings-callback' => 'it_exchange_quantity_discounts_addon_settings_callback',
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
function exchange_quanity_discounts_plugin_updater() {

	$license_check = get_transient( 'exchangewp_license_check' );

	if ($license_check->license == 'valid' ) {
		$license_key = it_exchange_get_option( 'exchangewp_licenses' );
		$license = $license_key['exchange_license'];

		$edd_updater = new EDD_SL_Plugin_Updater( 'https://exchangewp.com', __FILE__, array(
				'version' 		=> '0.0.1', 				// current version number
				'license' 		=> $license, 		// license key (used get_option above to retrieve from DB)
				'item_name' 	=> 'quanity-discounts', 	  // name of this plugin
				'author' 	  	=> 'ExchangeWP',    // author of this plugin
				'url'       	=> home_url(),
				'wp_override' => true,
				'beta'		  	=> false
			)
		);
	}

}

add_action( 'admin_init', 'exchange_quanity_discounts_plugin_updater', 0 );
