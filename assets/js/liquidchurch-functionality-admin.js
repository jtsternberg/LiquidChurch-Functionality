/**
 * LiquidChurch Admin Functionality
 * http://www.liquidchurch.com/
 *
 * Licensed under the GPLv2+ license.
 */

window.LiquidChurchAdmin = window.LiquidChurchAdmin || {};

( function( window, document, $, cmb, plugin ) {

	plugin.init = function() {
		var media = cmb.media;
		var box = document.getElementById( plugin.id + '_repeat' );
		var $group;

		var selectFile = function() {
			var selection = media.frames[ media.field ].state().get( 'selection' );
			var attachment = selection.first().toJSON();

			var type = attachment.type || attachment.filename.split( '.' ).pop();

			$group.find( '.cmb-type-text .regular-text' ).val( type );
		};

		var setupTypeListener = function( $row ) {
			var frameID = $row.find( '.cmb2-upload-file' ).attr( 'id' );

			$group = $row.closest( '.cmb-nested.cmb-field-list' );

			setTimeout( function() {
				var frame = media.frames[ frameID ];

				if ( frame && ! frame.setResourceType ) {
					frame.setResourceType = true;
					frame.on( 'select', selectFile );
				}
			}, 500 );
		};

		var clickButton = function() {
			if ( $.contains( box, this ) ) {
				setupTypeListener( $( this ).closest( '.cmb-td' ) );
			}
		};

		cmb.metabox().on( 'click', '.cmb2-upload-button', clickButton );
	};

	$( plugin.init );
}( window, document, jQuery, window.CMB2, window.LiquidChurchAdmin ) );
