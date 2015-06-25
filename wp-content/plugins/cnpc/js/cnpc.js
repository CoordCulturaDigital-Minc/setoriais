jQuery( function(){
	// ATTRIBUTES ///////////////////////////////////////////////////////////////////////////////////

	// METHODS //////////////////////////////////////////////////////////////////////////////////////
	/**
	 * mostrar/ocultar compo de upload quando o arquivo já tiver sido informado
	 *
	 * @name    show_hide
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-20
	 * @updated 2012-03-20
	 * @return  void
	 */
	function show_hide()
	{
		jQuery( '#cnpc .show-hide' ).each( function() {
			if( 'checked' == jQuery( this ).attr( 'checked' ) )
				jQuery( '#' + jQuery( this ).val() ).show( 'fast' );
			else
				jQuery( '#' + jQuery( this ).val() ).hide( 'fast' );
		} );
	}

	/**
	 * mostrar/ocultar compo de upload quando o arquivo já tiver sido informado
	 *
	 * @name    show_hide_inverse
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-04-30
	 * @updated 2012-04-30
	 * @return  void
	 */
	function show_hide_inverse()
	{
		jQuery( '#cnpc .show-hide-inverse' ).each( function() {
			if( 'checked' == jQuery( this ).attr( 'checked' ) )
				jQuery( '#' + jQuery( this ).val() ).hide( 'fast' );
			else
				jQuery( '#' + jQuery( this ).val() ).show( 'fast' );
		} );
	}

	/**
	 * conta a quantidade de caracteres
	 *
	 * @name    mb_strlen
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-22
	 * @updated 2012-03-22
	 * @return  void
	 */
	function mb_strlen( str )
	{
		var len = 0;

		for( var i = 0; i < str.length; i++ ) {
			len += str.charCodeAt( i ) < 0 || str.charCodeAt( i ) > 255 ? 2 : 1;
		}

		return len;
	}

	// CONSTRUCTOR //////////////////////////////////////////////////////////////////////////////////
	show_hide();
	show_hide_inverse();

	jQuery( '#cnpc .show-hide' ).change( function() {
		show_hide();
	} );

	jQuery( '#cnpc .show-hide-inverse' ).change( function() {
		show_hide_inverse();
	} );

	// limita a quantidade de caracteres
	jQuery( '#cnpc .limit-chars' ).each( function() {
		var limit       = jQuery( this ).attr( 'maxlength' );
		var text        = jQuery( this ).val();
		var text_length = mb_strlen( text );

		jQuery( this ).after( '<div class="limit-chars-counter">( ' + ( limit - text_length ) + ' )</div>' );

		jQuery( this ).keyup( function() {
			var text        = jQuery( this ).val();
			var text_length = mb_strlen( text );

			if( text_length > limit )
			{
				jQuery( this ).siblings( '.limit-chars-counter' ).html( '(<strong style="color:#ff0;">' + limit + '</strong>)' );
				jQuery( this ).val( text.substr( 0, limit ) );

				return false;
			}
			else
			{
				jQuery( this ).siblings( '.limit-chars-counter' ).html( '( ' + ( limit - text_length ) + ' )' );

				return true;
			}
		} );
  } );
} );