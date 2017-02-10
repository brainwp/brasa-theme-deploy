(function ( $ ) {
	'use strict';

	$(function () {
		// Deploy by Commit Hash
		$( '#brasa-deploy-by-commit' ).on( 'click', function( e ) {
			e.preventDefault();
			var $input_hash = $( 'input[name="brasa_deploy_force_hash"]' );
			var $button = $( this );
			var data = {
				action: 'brasa_deploy_by_commit',
				nonce: $input_hash.attr( 'data-nonce' ),
				hash: $input_hash.val(),
			};
			var default_text = $button.html();
			$button.html( $button.attr( 'data-load' ) );
			$.post( ajaxurl, data, function(response) {
				$button.html( default_text );
				$( '#brasa-deploy-status' ).append( response );
			});
			console.log( ajaxurl );
		});
	});

}(jQuery));
