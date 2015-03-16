<?php
/**
 * This will control Quantity Discounts
 * By default, it registers a metabox on the product's add/edit screen and provides HTML / data for the frontend.
 *
 * @since 1.0.0
 * @package IT_Exchange
*/
class IT_Exchange_Product_Feature_Quantity_Discounts extends IT_Exchange_Product_Feature_Abstract {

	/**
	 * Constructor. Registers hooks
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function IT_Exchange_Product_Feature_Quantity_Discounts( $args=array() ) {
		parent::__construct( $args );
	}

	/**
	 * This echos the feature metabox.
	 *
	 * @since 1.0.0
	 * @return void
	*/
	function print_metabox( $post ) {
		// Grab the iThemes Exchange Product object from the WP $post object
		$product = it_exchange_get_product( $post );

		// Set the value of the feature for this product
		$product_feature_enable_value = it_exchange_get_product_feature( $product->ID, 'quantity-discounts', array( 'setting' => 'enabled' ) );
		$product_feature_value        = it_exchange_get_product_feature( $product->ID, 'quantity-discounts' );

		// Set description
		$description = __( 'Use this feature to set bulk discounts for products.', 'LION' );
		$description = apply_filters( 'it_exchange_product_quantity_discounts_metabox_description', $description );

		// Get currency settings
		$settings = it_exchange_get_option( 'settings_general' );
		$currency = it_exchange_get_currency_symbol( $settings['default-currency'] );
		?>
			<?php if ( $description ) : ?>
				<p class="intro-description"><?php echo $description; ?></p>
			<?php endif; ?>
			<p>
				<input type="checkbox" id="it-exchange-enable-product-quantity-discounts" class="it-exchange-checkbox-enable" name="it-exchange-enable-product-quantity-discounts" <?php checked( 'yes', $product_feature_enable_value ); ?> /> <label for="it-exchange-enable-product-quantity-discounts">
					<?php _e( 'Enable Quantity Discounts for this Product', 'LION' ); ?>
				</label>
			</p>
			<div class="it-exchange-enable-product-quantity-discounts<?php echo ( $product_feature_enable_value == 'no' ) ? ' hide-if-js' : '' ?>">
				<div class="it-exchange-product-quantity-discount-fields">
					<div class="cell add-new-cell"><input class="button button-primary it-exchange-add-new-quantity-discount-button" type="button" value="Add new discount" /></div>
					<h4 class="existing-discount-label<?php echo empty( $product_feature_value ) ? ' hide-if-js' : ''; ?>"><?php _e( 'Existing Quantity Discounts', 'LION' ); ?></h4>
					<?php $int=1; if ( $product_feature_value ) : ?>
						<?php foreach( (array) $product_feature_value as $discount_data ) {
							?>
							<div class="discount-row existing-discount-row">
								<div class="title-row">
									<span class="title-text"><span class="title-text-quantity"><?php echo $discount_data['quantity']; ?></span> <?php _e( ' or more items'); ?></span></span>
									<span class="title-price"><?php echo it_exchange_format_price( it_exchange_convert_from_database_number( $discount_data['price'] ) ); ?></span>
									<span class="title-edit"></span>
								</div>
								<div class="it-exchange-quantity-discounts-content hidden">
									<div class="cell quantity-cell">
										<label><?php _e( 'Quantity', 'LION' ); ?></label>
										<input type="number" class="quantity-field" name="it-exchange-product-quantity-discounts[<?php esc_attr_e( $int ); ?>][quantity]" value="<?php esc_attr_e( $discount_data['quantity'] ); ?>" />
									</div>
									<div class="cell price-cell">
										<label><?php _e( 'Price', 'LION' ); ?></label>
										<input type="text" class="price-field" name="it-exchange-product-quantity-discounts[<?php esc_attr_e( $int ); ?>][price]" value="<?php esc_attr_e( it_exchange_format_price( it_exchange_convert_from_database_number( $discount_data['price'] ) ) ); ?>" data-thousands-separator="<?php esc_attr_e( $settings['currency-thousands-separator'] ); ?>" data-decimals-separator="<?php echo esc_attr_e( $settings['currency-decimals-separator'] ); ?>" data-symbol="<?php esc_attr_e( $currency ); ?>" data-symbol-position="<?php esc_attr_e( $settings['currency-symbol-position'] ); ?>"/>
									</div>
								</div>
								<a href="" class="it-exchange-delete-quantity-discount it-exchange-remove-item hide-if-js">&times;</a>
							</div>
							<?php
							$int++;
						} ?>
					<?php endif; ?>
					<div class="discount-row new-discount-row editing hide-if-js" data-int="<?php esc_attr_e( $int ); ?>">
						<div class="title-row">
							<span class="title-text hide-if-js"><span class="title-text-quantity"></span> <?php _e( ' or more items'); ?></span></span>
							<span class="title-price"></span>
							<span class="title-edit"></span>
						</div>
						<div class="it-exchange-quantity-discounts-content new-discount-content">
							<div class="cell quantity-cell">
								<label><?php _e( 'Quantity', 'LION' ); ?></label>
								<input type="text" class="quantity-field" name="it-exchange-product-quantity-discounts[<?php esc_attr_e( $int ); ?>][quantity]" value="" />
							</div>
							<div class="cell price-cell">
								<label><?php _e( 'Price', 'LION' ); ?></label>
								<input type="text" class="price-field" name="it-exchange-product-quantity-discounts[<?php esc_attr_e( $int ); ?>][price]" value="" data-thousands-separator="<?php esc_attr_e( $settings['currency-thousands-separator'] ); ?>" data-decimals-separator="<?php echo esc_attr_e( $settings['currency-decimals-separator'] ); ?>" data-symbol="<?php esc_attr_e( $currency ); ?>" data-symbol-position="<?php esc_attr_e( $settings['currency-symbol-position'] ); ?>"/>
							</div>
						</div>
						<a href="" class="it-exchange-delete-quantity-discount it-exchange-remove-item">&times;</a>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * This saves the value
	 *
	 * @since 1.0.0
	 *
	 * @param object $post wp post object
	 * @return void
	*/
	function save_feature_on_product_save() {
		// Abort if we can't determine a product type
		if ( ! $product_type = it_exchange_get_product_type() )
			return;

		// Abort if we don't have a product ID
		$product_id = empty( $_POST['ID'] ) ? false : $_POST['ID'];
		if ( ! $product_id )
			return;

		// Abort if this product type doesn't support this feature
		if ( ! it_exchange_product_type_supports_feature( $product_type, 'quantity-discounts' ) )
			return;

        // Save option for checkbox allowing quantity
        if ( empty( $_POST['it-exchange-enable-product-quantity-discounts'] ) )
			it_exchange_update_product_feature( $product_id, 'quantity-discounts', 'no', array( 'setting' => 'enabled' ) );
        else
			it_exchange_update_product_feature( $product_id, 'quantity-discounts', 'yes', array( 'setting' => 'enabled' ) );

		if ( isset( $_POST['it-exchange-product-quantity-discounts'] ) ) {
			// Remove empty values
			foreach( (array) $_POST['it-exchange-product-quantity-discounts'] as $key => $data ) {
				if ( empty( $data['quantity'] ) || empty( $data['price'] ) ) {
					unset( $_POST['it-exchange-product-quantity-discounts'][$key] );
				} else {
					$_POST['it-exchange-product-quantity-discounts'][$key]['price'] = it_exchange_convert_to_database_number( $data['price'] );
				}
			}

			// Sort by quantity
			usort( $_POST['it-exchange-product-quantity-discounts'], array( $this, 'sort_by_quantity' ) );

			// Save
			it_exchange_update_product_feature( $product_id, 'quantity-discounts', $_POST['it-exchange-product-quantity-discounts'] );
		}
	}

	/**
	 * Call back for the usort that sorts by quantity
	 *
	 * @since 1.0.1
	 *
	 * @return boolean
	*/
	function sort_by_quantity( array $a, array $b ) {
		return $b['quantity'] - $a['quantity'];
	}

	/**
	 * This updates the feature for a product
	 *
	 * @since 1.0.0
	 *
	 * @param integer $product_id the product id
	 * @param mixed $new_value the new value
	 * @return bolean
	*/
	function save_feature( $product_id, $new_value, $options=array() ) {
		// Using options to determine if we're setting the enabled setting or the actual max_number setting
		$defaults = array(
			'setting' => 'quantity-discounts',
		);
		$options = wp_parse_args( $options, $defaults );

		// Only accept settings for max_number (default) or 'enabled' (checkbox)
		if ( 'quantity-discounts' == $options['setting'] ) {
			update_post_meta( $product_id, '_it-exchange-product-quantity-discounts', $new_value );
			return true;
		} else if ( 'enabled' == $options['setting'] ) {
			// Enabled setting must be yes or no.
			if ( ! in_array( $new_value, array( 'yes', 'no' ) ) )
				$new_value = 'yes';
			update_post_meta( $product_id, '_it-exchange-product-enable-quantity-discounts', $new_value );
			return true;
		}
	}

	/**
	 * Return the product's features
	 *
	 * @since 1.0.0
	 * @param mixed $existing the values passed in by the WP Filter API. Ignored here.
	 * @param integer product_id the WordPress post ID
	 * @return string product feature
	*/
	function get_feature( $existing, $product_id, $options=array() ) {

        // Using options to determine if we're getting the enabled setting or the actual inventory number
        $defaults = array(
            'setting' => 'quantity-discounts',
        );
        $options = wp_parse_args( $options, $defaults );

        if ( 'enabled' == $options['setting'] ) {
            $enabled = get_post_meta( $product_id, '_it-exchange-product-enable-quantity-discounts', true );
            if ( ! in_array( $enabled, array( 'yes', 'no' ) ) )
                $enabled = 'no';
            return $enabled;
        } else if ( 'quantity-discounts' == $options['setting'] ) {
            if ( it_exchange_product_supports_feature( $product_id, 'quantity-discounts' ) )
                return get_post_meta( $product_id, '_it-exchange-product-quantity-discounts', true );
        }
        return false;
	}

	/**
	 * Does the product have this feature?
	 *
	 * @since 1.0.0
	 * @param mixed $result Not used by core
	 * @param integer $product_id
	 * @return boolean
	*/
	function product_has_feature( $result, $product_id, $options=array() ) {
		// Does this product type support this feature?
		if ( false === $this->product_supports_feature( false, $product_id ) )
			return false;
		return (boolean) $this->get_feature( false, $product_id );
	}

	/**
	 * Does the product support this feature?
	 *
	 * This is different than if it has the feature, a product can
	 * support a feature but might not have the feature set.
	 *
	 * @since 1.0.0
	 * @param mixed $result Not used by core
	 * @param integer $product_id
	 * @return boolean
	*/
	function product_supports_feature( $result, $product_id, $options=array() ) {
		// Does this product type support this feature?
		$product_type = it_exchange_get_product_type( $product_id );
		if ( it_exchange_product_type_supports_feature( $product_type, 'quantity-discounts' ) ) {
			if ( 'yes' === it_exchange_get_product_feature( $product_id, 'quantity-discounts', array( 'setting' => 'enabled' ) ) )
				return true;
		} else {
			return false;
		}
	}
}
$IT_Exchange_Product_Feature_Quantity_Discounts = new IT_Exchange_Product_Feature_Quantity_Discounts( array( 'slug' => 'quantity-discounts', 'metabox_title' => __( 'Quantity Discounts', 'LION' ), 'description' => __( 'Allows store owners the ability to set bulk discounts for products.', 'LION' ) ) );
