/* global jQuery, wp, tvAdmin */
/**
 * Turnverein Manager – admin scripts.
 *
 * - Media picker for the trainer image.
 * - Drag & drop sorting of the Sportstätten list.
 * - Click-to-copy for shortcode fields.
 */
( function ( $ ) {
	'use strict';

	// ---------------------------------------------------------------------
	// Trainer image (media library)
	// ---------------------------------------------------------------------
	$( document ).on( 'click', '.tv-bild-select', function ( e ) {
		e.preventDefault();

		var $wrap = $( this ).closest( '.tv-bild-field' );
		var frame = wp.media( {
			title: tvAdmin.i18n.mediaTitle,
			button: { text: tvAdmin.i18n.mediaButton },
			library: { type: 'image' },
			multiple: false
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var url = ( attachment.sizes && attachment.sizes.thumbnail ) ? attachment.sizes.thumbnail.url : attachment.url;

			$wrap.find( '.tv-bild-id' ).val( attachment.id );
			$wrap.find( '.tv-bild-preview' ).html( $( '<img>', { src: url, alt: '' } ) );
			$wrap.find( '.tv-bild-remove' ).show();
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.tv-bild-remove', function ( e ) {
		e.preventDefault();

		var $wrap = $( this ).closest( '.tv-bild-field' );
		$wrap.find( '.tv-bild-id' ).val( '0' );
		$wrap.find( '.tv-bild-preview' ).empty();
		$( this ).hide();
	} );

	// ---------------------------------------------------------------------
	// Sportstätten: drag & drop order
	// ---------------------------------------------------------------------
	$( function () {
		var $list = $( '#tv-sportstaetten-sortable' );
		if ( ! $list.length || ! $.fn.sortable ) {
			return;
		}

		var $status = $( '#tv-sort-status' );

		$list.sortable( {
			items: '> tr',
			handle: '.tv-sort-handle',
			axis: 'y',
			placeholder: 'tv-sort-placeholder',
			helper: function ( e, $row ) {
				// Keep cell widths while dragging.
				$row.children().each( function () {
					$( this ).width( $( this ).width() );
				} );
				return $row;
			},
			update: function () {
				var ids = $list.children( 'tr' ).map( function () {
					return $( this ).data( 'id' );
				} ).get();

				$.post( tvAdmin.ajaxUrl, {
					action: 'tv_sportstaetten_sort',
					nonce: tvAdmin.sortNonce,
					ids: ids
				} ).done( function ( res ) {
					$status.text( res && res.success ? tvAdmin.i18n.sortSaved : tvAdmin.i18n.sortError );
				} ).fail( function () {
					$status.text( tvAdmin.i18n.sortError );
				} );
			}
		} );
	} );

	// ---------------------------------------------------------------------
	// Shortcode fields: select on click
	// ---------------------------------------------------------------------
	$( document ).on( 'focus click', '.tv-shortcode-input', function () {
		this.select();
	} );
}( jQuery ) );
