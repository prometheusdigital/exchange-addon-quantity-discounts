<?php
/**
 * This file contains filters and actions used by the addon
 * @since 1.0.0
*/

/**
 * Modify the line item cost of a product in the cart based on quantity
 *
 * @since 1.0.0
 *
 * @param  string  $base_price the price passed through from the WP filter
 * @param  array   $product the cart product array 
 * @param  boolean $format format the price or no
 * @return string  $base_price the modified base price
*/
function it_exchange_modify_cart_product_base_price_for_quantity_discount_addon( $base_price, $product, $format ) {
	if ( ! $quantity_discounts = it_exchange_get_product_feature( $product['product_id'], 'quantity-discounts' ) )
		return $base_price;

	// Grab the quantity in the cart
	$cart_quantity = empty( $product['count'] ) ? 0 : (int) $product['count'];

	// Loop through (they're sorted from highest to lowest
	foreach( (array) $quantity_discounts as $key => $data ) {
		if ( $cart_quantity >= (int) $data['quantity'] ) {
			$price = preg_replace("/[^0-9,.]/", "", $data['price'] );
			return empty( $format ) ? $price : it_exchange_format_price( $price );
		}
	}
	return $base_price;
}
add_filter( 'it_exchange_get_cart_product_base_price', 'it_exchange_modify_cart_product_base_price_for_quantity_discount_addon', 10, 3 );
