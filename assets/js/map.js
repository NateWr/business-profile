/* global bpfwpMapVars, google */
/**
 * Front-end JavaScript for Business Profile maps
 *
 * @copyright Copyright (c) 2015, Theme of the Crop
 * @license   GPL-2.0+
 * @since     0.0.1
 */
function bpInitializeMap() {
	'use strict';

	bpfwpMapVars.maps = [];
	bpfwpMapVars.info_windows = [];

	jQuery( '.bp-map' ).each( function() {
		var id = jQuery(this).attr( 'id' );
		var data = jQuery(this).data();

		data.addressURI = encodeURIComponent( data.address.replace( /(<([^>]+)>)/ig, ', ' ) );

		// Google Maps API v3
		if ( 'undefined' !== typeof data.lat ) {
			latLon          = new google.maps.LatLng( data.lat, data.lon );
			data.addressURI = encodeURIComponent( data.address.replace( /(<([^>]+)>)/ig, ', ' ) );
			bpfwpMapVars.map_options = bpfwpMapVars.map_options || {};
			bpfwpMapVars.map_options.center = new google.maps.LatLng( data.lat, data.lon );
			if ( typeof bpfwpMapVars.map_options.zoom === 'undefined' ) {
				bpfwpMapVars.map_options.zoom = bpfwpMapVars.map_options.zoom || 15;
			}
			bpMaps[ id ]    = new google.maps.Map( document.getElementById( id ), bpfwpMapVars.map_options );

			var content = '<div class="bp-map-info-window">' +
				'<p><strong>' + data.name + '</strong></p>' +
				'<p>' + data.address.replace(/(?:\r\n|\r|\n)/g, '<br>') + '</p>';

			if ( typeof data.phone !== 'undefined' ) {
				content += '<p>' + data.phone + '</p>';
			}

			content += '<p><a target="_blank" href="//maps.google.com/maps?saddr=current+location&daddr=' + data.addressURI + '">' + strings.getDirections + '</a></p>' + '</div>';

			bpfwpMapVars.info_windows[ id ] = new google.maps.InfoWindow({
				position: bpfwpMapVars.map_options.center,
				content: content,
			});
			bpfwpMapVars.info_windows[ id ].open( bpfwpMapVars.maps[ id ]);

			// Trigger an intiailized event on this dom element for third-party code
			jQuery(this).trigger( 'bpfwp.map_initialized', [ id, bpfwpMapVars.maps[id], bpfwpMapVars.info_windows[id] ] );

		// Google Maps iframe embed (fallback if no lat/lon data available)
		} else if ( '' !== data.address ) {
			bpMapIframe = document.createElement( 'iframe' );

			var bpMapIframe = document.createElement( 'iframe' );
			bpMapIframe.frameBorder = 0;
			bpMapIframe.style.width = '100%';
			bpMapIframe.style.height = '100%';

			if ( '' !== data.name ) {
				data.address = data.name + ',' + data.address;
			}

			bpMapIframe.src = '//maps.google.com/maps?output=embed&q=' + encodeURIComponent( data.address );
			bpMapIframe.src = '//maps.google.com/maps?output=embed&q=' + data.addressURI;

			jQuery(this).html( bpMapIframe );

			// Trigger an intiailized event on this dom element for third-party code
			jQuery(this).trigger( 'bpfwp.map_initialized_in_iframe', [ jQuery(this) ] );
		}
	});
}

function bp_initialize_map() {
	bpInitializeMap();
}

jQuery( document ).ready(function() {

	// Allow developers to override the maps api loading and initializing
	if ( !bpfwpMapVars.autoload_google_maps ) {
		return;
	}
	// Load Google Maps API and initialize maps
	if ( typeof google === 'undefined' || typeof google.maps === 'undefined' ) {
		var bp_map_script = document.createElement( 'script' );
		bp_map_script.type = 'text/javascript';
		bp_map_script.src = '//maps.googleapis.com/maps/api/js?v=3.exp&callback=bp_initialize_map';
		document.body.appendChild( bp_map_script );

	// If the API is already loaded (eg - by a third-party theme or plugin),
	// just initialize the map
	} else {
		bp_initialize_map();
	}

	bpMapScript.type = 'text/javascript';
	bpMapScript.src  = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=bpInitializeMap';

	document.body.appendChild( bpMapScript );
});
