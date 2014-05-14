jQuery(document).ready(function($) {
	/**
	 * Add a new rule when clicked
	 */
	$('.it-exchange-add-new-quantity-discount-button').on('click', function(event) {
		event.preventDefault();

		var $newDiv = $('.new-discount-row', '.it-exchange-enable-product-quantity-discounts').clone();
		var oldInt  = $newDiv.attr('data-int');
		var newInt  = (oldInt * 1) + 1;

		// Clone new div from hidden div
		$newDiv
			.removeClass('new-discount-row hide-if-js')
			.addClass('existing-discount-row')
			.find('.it-exchange-add-new-quantity-discount-button')
				.remove()
				.end()
			.find('.it-exchange-quantity-discounts-content')
				.removeClass('new-discount-content')
				.end()
			.insertBefore( $('.new-discount-row', '.it-exchange-enable-product-quantity-discounts') );

		// Collapse any existing open content divs
		itExchangeQuantityDiscountsCloseAllContentDivs($newDiv);

		$('.existing-discount-label', '.it-exchange-enable-product-quantity-discounts').removeClass('hide-if-js');

		$('.new-discount-row', '.it-exchange-enable-product-quantity-discounts')
			.attr('data-int', newInt)
			.find('.quantity-field')
				.attr('name', 'it-exchange-product-quantity-discounts[' + newInt + '][quantity]')
				.val('')
				.end()
			.find('.price-field')
				.attr('name', 'it-exchange-product-quantity-discounts[' + newInt + '][price]')
				.val('')
				.end()
	});

	/**
	 * Toggle a div
	*/
	$('.it-exchange-enable-product-quantity-discounts').on('click', '.title-row', itExchangeQuantityDiscountsOpenDiv );

	$('.it-exchange-enable-product-quantity-discounts').on('click', '.it-exchange-delete-quantity-discount', function(event) {
		event.preventDefault();
		$(this).closest('.discount-row').fadeOut(400, function(){
			$(this).remove();
		
			if ( ! $('.existing-discount-row').length ) {
				$('.existing-discount-label', '.it-exchange-enable-product-quantity-discounts').addClass('hide-if-js');
			}
		});
	});

	// Format prices
    $('.it-exchange-enable-product-quantity-discounts').on( 'focusout', '.price-field', function() {
        if ( $( this ).data( 'symbol-position') == 'before' ) {
            $( this ).val( $( this ).data( 'symbol') + itExchangeQuatnityDiscountNumberFormat( $( this ).val(), 2, $( this ).data( 'decimals-separator' ), $( this ).data( 'thousands-separator' ) ) );
        } else {
            $( this ).val( itExchangeQuatnityDiscountNumberFormat( $( this ).val(), 2, $( this ).data( 'decimals-separator' ), $( this ).data( 'thousands-separator' ) ) + $( this ).data( 'symbol' ) );
		}
    })

	$('.price-field').trigger('focusout');

	//Closes all the content divs
	function itExchangeQuantityDiscountsCloseAllContentDivs(node) {
		node = typeof node !== 'undefined' ? node.find('.it-exchange-quantity-discounts-content') : false;
		$('.it-exchange-quantity-discounts-content')
			.not(node)
			.not('.new-discount-content')
			.slideUp( 400, function() {
				$(this).closest('.discount-row')
					.removeClass('editing')
					.find('.title-text-quantity')
						.text($(this).find('.quantity-field').val())
						.end()
					.find('.title-text')
						.removeClass('hide-if-js')
						.end()
					.find('.title-price')
						.text($(this).find('.price-field').val())
						.removeClass('hide-if-js')
						.end()
					.find('.it-exchange-delete-quantity-discount')
						.addClass('hide-if-js')
						.end();

			})
	}

	/**
	 * Open a quanity discount div
	*/
	function itExchangeQuantityDiscountsOpenDiv(event) {
		node = $(event.currentTarget).closest('.discount-row').find('.it-exchange-quantity-discounts-content');
		if ( typeof node == 'undefined' ) {
			return;
		}

		itExchangeQuantityDiscountsCloseAllContentDivs();
		if ( ! node.closest('.discount-row').hasClass('editing') ) {
			console.log('.discount-row has calss editing');
			node
				.slideDown(400, function() {
					$(this).closest('.discount-row')
						.addClass('editing')
						.find('.title-price')
							.addClass('hide-if-js')
							.end()
						.find('.it-exchange-delete-quantity-discount')
							.removeClass('hide-if-js')
							.end();
				});
		}
	}

	
	/**
	 * Format the currency fields
	*/
	function itExchangeQuatnityDiscountNumberFormat( number, decimals, dec_point, thousands_sep ) { 
		number = (number + '').replace(thousands_sep, ''); //remove thousands
		number = (number + '').replace(dec_point, '.'); //turn number into proper float (if it is an improper float)
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number;
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
		s = '', 
		toFixedFix = function (n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};  
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}   
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || ''; 
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}   
		return s.join(dec);
	}
});
