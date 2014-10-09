/* Frontend Javascript for Business Profile maps */
jQuery(document).ready(function ($) {

	// Load Google Maps API and initialize maps
	var bp_map_script = document.createElement( 'script' );
	bp_map_script.type = 'text/javascript';
	bp_map_script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=bp_initialize_map';
	document.body.appendChild( bp_map_script );

});

function bp_initialize_map() {

	var bp_maps = [];
	var bp_info_windows = [];

	jQuery( '.bp-map' ).each( function() {
		var id = jQuery(this).attr( 'id' );
		var data = jQuery(this).data();
		
		// Remove break tags froms address that get added in the editor
		var cleanAddress = data.address;
		cleanAddress = cleanAddress.replace(/(<|&lt;)br\s*\/*(>|&gt;)/g,' ');

		// Google Maps API v3
		if ( typeof data.lat !== 'undefined' ) {
			var latlon = new google.maps.LatLng( data.lat, data.lon );
			var bp_map_options = {
				zoom: 15,
				center: latlon,
			};

			bp_maps[ id ] = new google.maps.Map( document.getElementById( id ), bp_map_options );
			
			var content = '<div class="bp-map-info-window">' +
				'<p><strong>' + data.name + '</strong></p>' + 
				'<p>' + data.address + '</p>';
				
			if ( typeof data.phone !== 'undefined' ) {
				content += '<p>' + data.phone + '</p>';
			}
			content += '<p><a target="_blank" href="//maps.google.com/maps?saddr=current+location&daddr=' + encodeURIComponent( cleanAddress ) + '">Get Directions</a></p>' +
				'</div>';
			
			bp_info_windows[ id ] = new google.maps.InfoWindow({
				position: latlon,
				content: content,
			});
			bp_info_windows[ id ].open( bp_maps[ id ]);

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
		}
	});
}
