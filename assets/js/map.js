
function bpInitializeMap() {
	'use strict';

	jQuery( '.bp-map' ).each(function( $ ) {
		var bpMaps        = [],
			bpInfoWindows = [],
			$that         = $( this ),
			id            = $that.attr( 'id' ),
			data          = $that.data(),
			latLon, bpMapOptions, content, bpMapIframe;

		// Google Maps API v3
		if ( 'undefined' !== typeof data.lat ) {
			latLon = new google.maps.LatLng( data.lat, data.lon );
			bpMapOptions = {
				zoom:   15,
				center: latLon
			};

			bpMaps[ id ] = new google.maps.Map( document.getElementById( id ), bpMapOptions );

			content = '<div class="bp-map-info-window">' + '<p><strong>' + data.name + '</strong></p>' + '<p>' + data.address + '</p>';
			if ( 'undefined' !== typeof data.phone ) {
				content += '<p>' + data.phone + '</p>';
			}
			content += '<p><a target="_blank" href="//maps.google.com/maps?saddr=current+location&daddr=' + encodeURIComponent( data.address ) + '">Get Directions</a></p>' + '</div>';

			bpInfoWindows[ id ] = new google.maps.InfoWindow( {
				position: latLon,
				content:  content
			} );
			bpInfoWindows[ id ].open( bpMaps[ id ] );

		// Google Maps iframe embed (fallback if no lat/lon data available)
		} else if ( '' !== typeof data.address ) {
			bpMapIframe = document.createElement( 'iframe' );

			bpMapIframe.frameBorder  = 0;
			bpMapIframe.style.width  = '100%';
			bpMapIframe.style.height = '100%';

			if ( '' !== typeof data.name ) {
				data.address = data.name + ',' + data.address;
			}
			bpMapIframe.src = '//maps.google.com/maps?output=embed&q=' + encodeURIComponent( data.address );

			$that.html( bpMapIframe );
		}
	});
}

/* Frontend Javascript for Business Profile maps */
jQuery( document ).ready(function() {
	// Load Google Maps API and initialize maps
	var bpMapScript = document.createElement( 'script' );

	bpMapScript.type = 'text/javascript';
	bpMapScript.src  = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=bpInitializeMap';

	document.body.appendChild( bpMapScript );
});
