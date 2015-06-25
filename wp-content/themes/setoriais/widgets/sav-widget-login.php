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
 * Plugin Name: SAv: Widget Login
 * Plugin URI: http://marcelomesquita.com/
 * Description:
 * Author: Marcelo Mesquita
 * Version: 2012.05.09
 * Author URI: http://marcelomesquita.com/
 */

class SAv_Widget_Login extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-05-09
	 * @updated 2012-05-10
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function widget( $args, $instance )
	{
		global $wpdb;

		print $args[ 'before_widget' ];

		if( !empty( $instance[ 'title' ] ) )
		{
			print $args[ 'before_head' ];
			print $args[ 'before_title' ] . $instance[ 'title' ] . $args[ 'after_title' ];
			print $args[ 'after_head' ];
		}

		print $args[ 'before_body' ];

		if( is_user_logged_in() ) :

			global $current_user;

			?>
				<div class="greetings">
					<p>Olá, <?php print $current_user->display_name; ?> <a href="<?php print wp_logout_url( site_url() ); ?>" title="sair">sair</a></p>
				</div>
				<div class="clear"></div>
			<?php

		else :

			?>
				<div class="login">
					<form action="<?php print wp_login_url( site_url() ); ?>" method="post">
						<input type="text" id="user_login" name="log" value="CPF" class="memory" />
						<input type="password" id="user_pass" name="pwd" value="senha" /><button type="submit" name="wp-submit" id="wp-submit" value="Login">OK</button>
						<a href="<?php print wp_lostpassword_url(); ?>" title="Esqueceu a senha?" class="forget">Esqueceu a senha?</a>
					</form>
				</div>
				<div class="register">
					<a href="<?php print site_url( '/cadastro/' ); ?>" title="Ainda não possuo cadastro">Ainda <span>não</span> possuo cadastro.</a>
				</div>
				<div class="clear"></div>
			<?php

		endif;

		print $args[ 'after_body' ];
		print $args[ 'after_widget' ];
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
	 * @since   2012-05-09
	 * @updated 2012-05-09
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

		<?php
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    SAv_Widget_Login
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-05-09
	 * @updated 2012-05-09
	 * @return  void
	 */
	function SAv_Widget_Login()
	{
		// register widget
		$this->WP_Widget( 'login', 'SAv: Login' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "SAv_Widget_Login" );' ) );

?>
