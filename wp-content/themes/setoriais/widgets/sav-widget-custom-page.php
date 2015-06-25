<?php

/**
 * Copyright (c) 2012 Marcelo Mesquita
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
 * Plugin Name: SAv: Widget Custom Page
 * Plugin URI: http://marcelomesquita.com/
 * Description: Allow the creation of a custom loop.
 * Author: Marcelo Mesquita
 * Version: 2012.05.04
 * Author URI: http://marcelomesquita.com/
 */

class SAv_Widget_Custom_Page extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-05-04
	 * @updated 2012-05-04
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

			$area      = str_replace( array( $site_path, '/' ), '', $blog_path );
		}

		$blog_url = get_bloginfo( 'url' );

		// load posts
		$custom_loop = new WP_Query( "ignore_sticky_posts=1&page_id={$instance[ 'page' ]}" );

		// show posts
		if( $custom_loop->have_posts() )
		{
			print "<div class='{$area}'>";
			print $args[ 'before_widget' ];

			if( !empty( $instance[ 'title' ] ) )
			{
				print $args[ 'before_head' ];
				print "<a href='{$blog_url}' title='click para ver mais' class='more'>mais</a>";
				print $args[ 'before_title' ] . $instance[ 'title' ] . $args[ 'after_title' ];
				print $args[ 'after_head' ];
			}

			print $args[ 'before_body' ];

			// está pegando o link do loop padrão
			//$before_loop = preg_replace_callback( '/\{next ?(text=[\'\"]([^\}]+)[\'\"])?\}/', create_function( '$matches', 'return get_next_posts_link( $matches[ 2 ] );' ), $before_loop );
			//$before_loop = preg_replace_callback( '/\{prev ?(text=[\'\"]([^\}]+)[\'\"])?\}/', create_function( '$matches', 'return get_previous_posts_link( $matches[ 2 ] );' ), $before_loop );

			while( $custom_loop->have_posts() )
			{
				$custom_loop->the_post();

				$loop = $instance[ 'loop' ];

				//$loop = str_replace( '{title}', get_the_title(), $loop );
				$loop = preg_replace_callback( '/\{title ?(length=[\'\"]([0-9]+)[\'\"])?\}/U', create_function( '$matches', 'return limit_chars( get_the_title(), $matches[ 2 ] );' ), $loop );
				//$loop = str_replace( '{excerpt}', get_the_excerpt(), $loop );
				$loop = preg_replace_callback( '/\{excerpt ?(length=[\'\"]([0-9]+)[\'\"])?\}/U', create_function( '$matches', 'return limit_chars( get_the_excerpt(), $matches[ 2 ] );' ), $loop );
				$loop = str_replace( '{permalink}', get_permalink(), $loop );
				$loop = str_replace( '{content}', get_the_content(), $loop );
				$loop = str_replace( '{author}', get_the_author(), $loop );
				$loop = str_replace( '{author-permalink}', get_author_posts_url( $post->post_author ), $loop );
				$loop = str_replace( '{date}', get_the_time( get_option( 'date_format' ) ), $loop );
				$loop = str_replace( '{time}', get_the_time( get_option( 'time_format' ) ), $loop );
				$loop = preg_replace_callback( '/\{thumb ?(size=[\'\"]([^\'\"]+)[\'\"])? ?(attr=[\'\"]([^\}]*)[\'\"])?\}/U', create_function( '$matches', 'return get_the_post_thumbnail( NULL, $matches[ 2 ], $matches[ 4 ] );' ), $loop );
				$loop = preg_replace_callback( '/\{meta ?(key=[\'\"]([^\'\"]+)[\'\"])?\}/U', create_function( '$matches', 'return get_post_meta( ' . $post->ID . ', $matches[ 2 ], true );' ), $loop );

				print $loop;
			}

			// está pegando o link do loop padrão
			//$after_loop = preg_replace_callback( '/\{next ?(text=[\'\"]([^\}]+)[\'\"])?\}/', create_function( '$matches', 'return get_next_posts_link( $matches[ 2 ] );' ), $before_loop );
			//$after_loop = preg_replace_callback( '/\{prev ?(text=[\'\"]([^\}]+)[\'\"])?\}/', create_function( '$matches', 'return get_previous_posts_link( $matches[ 2 ] );' ), $after_loop );

			print $args[ 'after_body' ];
			print $args[ 'after_widget' ];
			print '</div>';
		}

		if( function_exists( 'restore_current_blog' ) ) restore_current_blog();
	}

	/**
	 * update data
	 *
	 * @name    update
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-05-04
	 * @updated 2012-05-04
	 * @param   array $new_instance - new values
	 * @param   array $old_instance - old values
	 * @return  array
	 */
	function update( $new_instance, $old_instance )
	{
		if( empty( $new_instance[ 'loop' ] ) )
		{
			$loop_model = get_option( 'loop_model' );

			if( empty( $loop_model ) )
			{
				$loop_model = '<div class="post"><h1 class="post-title"><a href="{permalink}" title="{title}">{title}</a></h1><div class="post-entry entry"><a href="{permalink}" title="{title}">{excerpt}</a></div></div>';

				update_option( 'loop_model', $loop_model );
			}

			$new_instance[ 'loop' ] = $loop_model;
		}

		return $new_instance;
	}

	/**
	 * widget options form
	 *
	 * @name    form
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-05-04
	 * @updated 2012-05-04
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

			<p>
				<label for="<?php print $this->get_field_id( 'page' ); ?>"><?php _e( 'Page' ); ?>:</label><br />
				<?php if( function_exists( 'switch_to_blog' ) ) switch_to_blog( $instance[ 'blog' ] ); ?>
					<?php wp_dropdown_pages( "id=" . $this->get_field_id( 'page' ) . "&name=" . $this->get_field_name( 'page' ) . "&selected={$instance[ 'page' ]}" ); ?>
				<?php if( function_exists( 'restore_current_blog' ) ) restore_current_blog(); ?>
			</p>

			<p>
				<label for="<?php print $this->get_field_id( 'loop' ); ?>"><?php _e( 'Loop' ); ?>:</label>
				<textarea id="<?php print $this->get_field_id( 'loop' ); ?>" name="<?php print $this->get_field_name( 'loop' ); ?>" cols="23" rows="5" class="widefat"><?php print $instance[ 'loop' ]; ?></textarea>
				<small><?php _e( 'You can use any of this shortcodes:' ); ?> {title [length='100']} {excerpt [length='100']} {permalink} {content} {author} {author-permalink} {categories} {tags} {date} {time} {datetime [format='format']} {thumb [size='thumbnail']} {meta key='meta'}</small>
			</p>
		<?php
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    SAv_Widget_Custom_Page
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-05-04
	 * @updated 2012-05-04
	 * @return  void
	 */
	function SAv_Widget_Custom_Page()
	{
		// define plugin path
		$this->path = dirname( __FILE__ ) . '/';

		// register widget
		$this->WP_Widget( 'custom_page', 'SAV: Custom Page', array( 'classname' => 'widget_custom_loop', 'description' => __( 'Allow the creation of a custom loop', 'widget-custom-page' ) ), array( 'width' => 400 ) );

		// includes
		if( !function_exists( 'limit_chars' ) )
			include( $this->path . 'inc/limit-chars.php' );

		include_once( ABSPATH . WPINC . '/post-thumbnail-template.php' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "SAv_Widget_Custom_Page" );' ) );

?>
