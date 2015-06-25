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
		jQuery( '.show-hide' ).each( function() {
			if( 'checked' == jQuery( this ).attr( 'checked' ) )
				jQuery( '#' + jQuery( this ).val() ).show( 'fast' );
			else
				jQuery( '#' + jQuery( this ).val() ).hide( 'fast' );
		} );
	}

	// CONSTRUCTOR //////////////////////////////////////////////////////////////////////////////////
	show_hide();

	jQuery( '.show-hide' ).change( function() {
		show_hide();
	} );

	// font size
	jQuery( '.section' ).jfontsize( {
		btnPlusClasseId:    '.increase-font',
		btnMinusClasseId:   '.decrease-font',
		btnDefaultClasseId: '.default-font'
	});

	// memory
	jQuery( 'input.memory' ).each( function() {
		var memory_value = jQuery( this ).val();

		jQuery( this ).focusin( function() {
			if( jQuery( this ).val() == memory_value )
			{
				jQuery( this ).val( '' );
			}
		} );

		jQuery( this ).focusout( function() {
			if( jQuery( this ).val() == '' )
			{
				jQuery( this ).val( memory_value );
			}
		} );
	} );

	// cycle
  jQuery( '.section-cycle .section-body' ).cycle({
    fx:        'fade',
    timeout:   '5000',
    pager:     '.section-cycle .pagination',
    pause:     1,
    cleartype: 1
  });
} );
