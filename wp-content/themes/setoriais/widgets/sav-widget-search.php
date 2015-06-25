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
 * Plugin Name: SAv: Widget Search
 * Plugin URI: http://marcelomesquita.com/
 * Description:
 * Author: Marcelo Mesquita
 * Version: 2012.03.29
 * Author URI: http://marcelomesquita.com/
 */

class SAv_Widget_Search extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2011-11-20
	 * @updated 2012-03-30
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function widget( $args, $instance )
	{
		global $wpdb;

		if( function_exists( 'switch_to_blog' ) ) switch_to_blog( $instance[ 'blog' ] );

		if( is_multisite() )
		{
			$site_path = $wpdb->get_var( $wpdb->prepare( "SELECT path FROM {$wpdb->blogs} WHERE blog_id = 1" ) );
			$blog_path = $wpdb->get_var( $wpdb->prepare( "SELECT path FROM {$wpdb->blogs} WHERE blog_id = %d", get_current_blog_id() ) );

			$widget_class = str_replace( array( $site_path, '/' ), '', $blog_path );
		}

		print $args[ 'before_widget' ];

		if( !empty( $instance[ 'title' ] ) )
		{
			print $args[ 'before_head' ];
			print $args[ 'before_title' ] . $instance[ 'title' ] . $args[ 'after_title' ];
			print $args[ 'after_head' ];
		}

		print $args[ 'before_body' ];

		?>
			<form action="<?php bloginfo( 'url' ); ?>" method="get">
				<label for="s" class="invisible">pesquisar</label>
				<input type="text" id="s" name="s" class="memory" value="<?php print !get_search_query() ? 'o que procura?' : get_search_query(); ?>" />
				<?php wp_dropdown_categories( 'show_option_all=Categorias&hierarchical=true' ); ?>
				<button type="submit">ok</button>
			</form>
		<?php

		print $args[ 'after_body' ];
		print $args[ 'after_widget' ];

		if( function_exists( 'restore_current_blog' ) ) restore_current_blog();
	}

	/**
	 * update data
	 *
	 * @name    update
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2011-11-20
	 * @updated 2011-11-20
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
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2011-11-20
	 * @updated 2011-11-30
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function form( $instance )
	{
		global $wpdb;

		?>
			<p>
				<label for="<?php print $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?>:</label>
				<input type="text" id="<?php print $this->get_field_id( 'title' ); ?>" name="<?php print $this->get_field_name( 'title' ); ?>" maxlength="26" value="<?php print $instance[ 'title' ]; ?>" class="widefat" />
			</p>

			<?php if( is_multisite() ) : ?>
				<?php if( empty( $instance[ 'blog' ] ) ) $instance[ 'blog' ] = get_current_blog_id(); ?>
				<?php $blogs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->blogs} WHERE public = 1 ORDER BY blog_id" ) ); ?>
				<p>
					<label for="<?php print $this->get_field_id( 'blog' ); ?>"><?php _e( 'Blog' ); ?>:</label><br />
					<select id="<?php print $this->get_field_id( 'blog' ); ?>" name="<?php print $this->get_field_name( 'blog' ); ?>">
						<?php foreach( $blogs as $blog ) : ?>
							<option value="<?php print $blog->blog_id; ?>" <?php if( $blog->blog_id == $instance[ 'blog' ] ) print 'selected="selected"'; ?>"><?php print "{$blog->domain}{$blog->path}"; ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>

		<?php
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    SAv_Widget_Search
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2011-11-20
	 * @updated 2011-11-22
	 * @return  void
	 */
	function SAv_Widget_Search()
	{
		// register widget
		$this->WP_Widget( 'sav_search', 'SAv: Busca' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "SAv_Widget_Search" );' ) );

?>
