<?php

/**
 * Copyright (c) 2012 Ministério da Cultura do Brasil
 *
 * Written by Marcelo Mesquita <marcelo.costa@cultura.gov.br>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 *
 * Public License can be found at http://www.gnu.org/copyleft/gpl.html
 *
 * Plugin Name: SAv: Widget Text
 * Plugin URI: http://marcelomesquita.com/
 * Description:
 * Author: Marcelo Mesquita
 * Version: 2012.07-05
 * Author URI: http://marcelomesquita.com/
 */

class SAv_Widget_Contador extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2011-11-23
	 * @updated 2012-07-05
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function widget( $args, $instance )
	{
		$eleitores  = get_users( 'meta_key=candidatura&meta_value=candidatura&meta_compare=!=' );
		$candidatos = get_users( 'meta_key=candidatura&meta_value=candidatura' );

		// show tags
		print $args[ 'before_widget' ];
		print $args[ 'before_body' ];

		?>
			<h1><?php print count( $eleitores ); ?> eleitores e</h1>
			<h1><?php print count( $candidatos ); ?> candidatos</h1>
			<h2 class="contador">cadastrados até o momento.</h2>
		<?php

		print $args[ 'after_body' ];
		print $args[ 'after_widget' ];
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    SAv_Widget_Contador
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-07-05
	 * @updated 2012-07-05
	 * @return  void
	 */
	function SAv_Widget_Contador()
	{
		// register widget
		$this->WP_Widget( 'contador', 'SAV: Contador' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "SAv_Widget_Contador" );' ) );

?>
