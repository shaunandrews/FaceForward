jQuery( document ).ready( function( $ ) {
	$( '.sidebar-toggle' ).click( function() {
		$( this ).toggleClass( 'active' );
		$( '#main' ).toggleClass( 'sidebar' );
	});

	$( '#site-navigation' ).waypoint( function() {
		$( '#masthead' ).toggleClass( 'stuck' );
		$( 'body' ).toggleClass( 'stuck' );
	});

	$('a:empty').remove();
	$('p:empty').remove();
});