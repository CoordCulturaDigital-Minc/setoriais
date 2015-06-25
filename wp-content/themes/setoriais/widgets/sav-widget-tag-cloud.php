<?php

/**
 * Copyright (c) 2012 Ministério da Cultura do Brasil
 *
 * Written by Marcelo Mesquita <stallefish@gmail.com>
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
 * Plugin Name: SAv: Widget Tag Cloud
 * Plugin URI: http://marcelomesquita.com/
 * Description:
 * Author: Marcelo Mesquita
 * Version: 2012.03.29
 * Author URI: http://marcelomesquita.com/
 */

class SAv_Widget_Tag_Cloud extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
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

		wp_tag_cloud( "smallest={$instance[ 'smallest' ]}&largest={$instance[ 'largest' ]}&number={$instance[ 'number' ]}" );

		print $args[ 'after_body' ];
		print $args[ 'after_widget' ];
	}

	/**
	 * update data
	 *
	 * @name    update
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2011-11-23
	 * @updated 2011-11-23
	 * @param   array $new_instance - new values
	 * @param   array $old_instance - old values
	 * @return  array
	 */
	function update( $new_instance, $old_instance )
	{
		if( empty( $new_instance[ 'smallest' ] ) )
			$new_instance[ 'smallest' ] = 8;

		if( empty( $new_instance[ 'largest' ] ) )
			$new_instance[ 'largest' ] = 22;

		if( empty( $new_instance[ 'number' ] ) )
			$new_instance[ 'number' ] = 20;

		return $new_instance;
	}

	/**
	 * widget options form
	 *
	 * @name    form
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2011-11-23
	 * @updated 2011-11-23
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
				<label for="<?php print $this->get_field_id( 'smallest' ); ?>"><?php _e( 'Smallest' ); ?>:</label><br />
				<input type="text" id="<?php print $this->get_field_id( 'smallest' ); ?>" name="<?php print $this->get_field_name( 'smallest' ); ?>" maxlength="2" size="5" value="<?php print $instance[ 'smallest' ]; ?>" />
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'largest' ); ?>"><?php _e( 'Largest' ); ?>:</label><br />
				<input type="text" id="<?php print $this->get_field_id( 'largest' ); ?>" name="<?php print $this->get_field_name( 'largest' ); ?>" maxlength="2" size="5" value="<?php print $instance[ 'largest' ]; ?>" />
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'number' ); ?>"><?php _e( 'Number' ); ?>:</label><br />
				<input type="text" id="<?php print $this->get_field_id( 'number' ); ?>" name="<?php print $this->get_field_name( 'number' ); ?>" maxlength="2" size="5" value="<?php print $instance[ 'number' ]; ?>" />
			</p>
		<?php
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    SAv_Widget_Tag_Cloud
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2011-11-23
	 * @updated 2011-11-23
	 * @return  void
	 */
	function SAv_Widget_Tag_Cloud()
	{
		// register widget
		$this->WP_Widget( 'tag_cloud', 'SAv: Nuvem de Tags' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "SAv_Widget_Tag_Cloud" );' ) );

?>
