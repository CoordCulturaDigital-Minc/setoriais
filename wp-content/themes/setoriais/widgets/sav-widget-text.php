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
 * Version: 2012.03.30
 * Author URI: http://marcelomesquita.com/
 */

class SAv_Widget_Text extends WP_Widget
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
	 * @updated 2012-12-30
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function widget( $args, $instance )
	{
		// show tags
		print $args[ 'before_widget' ];

		if( !empty( $instance[ 'title' ] ) )
		{
			print $args[ 'before_head' ];
			print $args[ 'before_title' ] . $instance[ 'title' ] . $args[ 'after_title' ];
			print $args[ 'after_head' ];
		}

		print $args[ 'before_body' ];

		print $instance[ 'text' ];

		print $args[ 'after_body' ];
		print $args[ 'after_widget' ];
	}

	/**
	 * update data
	 *
	 * @name    update
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2011-11-23
	 * @updated 2012-03-30
	 * @param   array $new_instance - new values
	 * @param   array $old_instance - old values
	 * @return  array
	 */
	function update( $new_instance, $old_instance )
	{
		return $new_instance;
	}

	/**
	 * widget options form
	 *
	 * @name    form
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2011-11-23
	 * @updated 2012-03-30
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function form( $instance )
	{
		?>
			<p>
				<label for="<?php print $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?>:</label>
				<input type="text" id="<?php print $this->get_field_id( 'title' ); ?>" name="<?php print $this->get_field_name( 'title' ); ?>" maxlength="26" value="<?php print $instance[ 'title' ]; ?>" class="widefat" />
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'text' ); ?>"><?php _e( 'Text' ); ?>:</label>
				<textarea id="<?php print $this->get_field_id( 'text' ); ?>" name="<?php print $this->get_field_name( 'text' ); ?>" cols="23" rows="5" class="widefat"><?php print $instance[ 'text' ]; ?></textarea>
			</p>
		<?php
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    SAv_Widget_Text
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2011-11-23
	 * @updated 2012-03-30
	 * @return  void
	 */
	function SAv_Widget_Text()
	{
		// register widget
		$this->WP_Widget( 'text', 'SAV: Texto', array(), array( 'width' => 400 ) );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "SAv_Widget_Text" );' ) );

?>
