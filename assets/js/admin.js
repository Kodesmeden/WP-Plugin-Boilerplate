jQuery( document ).ready( function( $ ) {

	var custom_uploader;

	$( '.remove-image' ).on( 'click', function( e ) {
		var object_id = $( this ).data( 'id' );
		var $input_object = $( '#'+object_id );

		$input_object.val( '' );
		$( '#'+object_id+'-preview .image' ).html( '' );
		$( this ).hide( 0 );
	} );

	$( '.upload-image' ).on( 'click', function( e ) {
		e.preventDefault();

		var object_id = $( this ).data( 'id' );
		var $input_object = $( '#'+object_id );
		var $remove_button = $( this ).next();

		if ( custom_uploader ) {
			custom_uploader.open();
			return;
		}

		custom_uploader = wp.media.frames.file_frame = wp.media( {
			title: meta_image.title,
			button: {
				text: meta_image.button
			},
			multiple: false
		} );

		custom_uploader.on( 'select', function() {
			attachment = custom_uploader.state().get( 'selection' ).first().toJSON();

			var image_url = attachment.url;

			if( attachment.sizes.medium !== undefined ){
				image_url = attachment.sizes.medium.url;
			}

			$input_object.val( attachment.id );
			$( '#'+object_id+'-preview .image' ).html( '<img src="'+image_url+'" alt="">' );
			$remove_button.show( 0 );
		} );

		custom_uploader.open();
	} );

	$( '.color-picker' ).wpColorPicker();
	
	$( 'select.styled-select' ).select2();
	
} );
