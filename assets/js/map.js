/* Frontend Javascript for Business Profile maps */
var bpfwp_map = bpfwp_map || {};

jQuery(document).ready(function ($) {

	// Allow developers to override the maps api loading and initializing
	if ( !bpfwp_map.autoload_google_maps ) {
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

});

function bp_initialize_map() {

	bpfwp_map.maps = [];
	bpfwp_map.info_windows = [];

	jQuery( '.bp-map' ).each( function() {
		var id = jQuery(this).attr( 'id' );
		var data = jQuery(this).data();

		// Google Maps API v3
		if ( typeof data.lat !== 'undefined' ) {
			bpfwp_map.map_options = bpfwp_map.map_options || {};
			bpfwp_map.map_options.center = new google.maps.LatLng( data.lat, data.lon );
			if ( typeof bpfwp_map.map_options.zoom === 'undefined' ) {
				bpfwp_map.map_options.zoom = bpfwp_map.map_options.zoom || 15;
			}

			bpfwp_map.maps[ id ] = new google.maps.Map( document.getElementById( id ), bpfwp_map.map_options );

			var content = '<div class="bp-map-info-window">' +
				'<p><strong>' + data.name + '</strong></p>' +
				'<p>' + data.address.replace(/(?:\r\n|\r|\n)/g, '<br>') + '</p>';

			if ( typeof data.phone !== 'undefined' ) {
				content += '<p>' + data.phone + '</p>';
			}
			content += '<p><a target="_blank" href="//maps.google.com/maps?saddr=current+location&daddr=' + encodeURIComponent( data.address ) + '">Get Directions</a></p>' +
				'</div>';

			bpfwp_map.info_windows[ id ] = new google.maps.InfoWindow({
				position: bpfwp_map.map_options.center,
				content: content,
			});
			bpfwp_map.info_windows[ id ].open( bpfwp_map.maps[ id ]);

			// Trigger an intiailized event on this dom element for third-party code
			jQuery(this).trigger( 'bpfwp.map_initialized', [ id, bpfwp_map.maps[id], bpfwp_map.info_windows[id] ] );

		// Google Maps iframe embed (fallback if no lat/lon data available)
		} else if ( typeof data.address !== '' ) {
			var bp_map_iframe = document.createElement( 'iframe' );
			bp_map_iframe.frameBorder = 0;
			bp_map_iframe.style.width = '100%';
			bp_map_iframe.style.height = '100%';

			if ( typeof data.name !== '' ) {
				data.address = data.name + ',' + data.address;
			}
			bp_map_iframe.src = '//maps.google.com/maps?output=embed&q=' + encodeURIComponent( data.address );

			jQuery(this).html( bp_map_iframe );

			// Trigger an intiailized event on this dom element for third-party code
			jQuery(this).trigger( 'bpfwp.map_initialized_in_iframe', [ jQuery(this) ] );
		}
	});
}
