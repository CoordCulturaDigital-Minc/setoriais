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
 * Plugin Name: SAv: Widget Candidatos
 * Plugin URI: http://marcelomesquita.com/
 * Description:
 * Author: Marcelo Mesquita
 * Version: 2012.05.17
 * Author URI: http://marcelomesquita.com/
 */

class SAv_Widget_Candidatos extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-12-30
	 * @updated 2012-05-17
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function widget( $args, $instance )
	{
		global $wpdb, $user_ID;

		$estado = get_user_meta( $user_ID, 'estado', true );

		if( !empty( $estado ) )
			$meus_candidatos = "&meta_key=estado&meta_value={$estado}";

		$candidatos = get_users( "role=candidato{$meus_candidatos}" );

		if( empty( $candidatos ) )
			return false;

		shuffle( $candidatos );

		// show tags
		print $args[ 'before_widget' ];

		if( !empty( $instance[ 'title' ] ) )
		{
			print $args[ 'before_head' ];
			print $args[ 'before_title' ] . $instance[ 'title' ] . $args[ 'after_title' ];
			print $args[ 'after_head' ];
		}

		print $args[ 'before_body' ];

		foreach( $candidatos as $candidato )
		{
			$candidato_metas = $wpdb->get_row( $wpdb->prepare( "SELECT nome, apelido, atuacao, cidade, estado FROM {$wpdb->cnpc_dados_pessoais} AS pes JOIN {$wpdb->cnpc_dados_profissionais} AS pro ON ( pes.login = pro.login ) JOIN {$wpdb->cnpc_dados_geograficos} AS geo ON ( pes.login = geo.login ) WHERE pes.login = %s", $candidato->user_login ) );

			?>
				<div class="user">
					<?php print get_avatar( $candidato->user_email, '60' ); ?>
					<h1 class="user-name"><a href="<?php print get_author_posts_url( $candidato->ID ); ?>" title="<?php print $candidato->display_name; ?>"><?php print $candidato->display_name; ?></a></h1>
					<div class="user-meta"><?php print $candidato_metas->nome; ?> é <?php print $candidato_metas->atuacao; ?> atua em <?php print $candidato_metas->cidade; ?> - <?php print $candidato_metas->estado; ?>.</div>
					<a href="<?php print get_author_posts_url( $candidato->ID ); ?>" title="<?php print $candidato->display_name; ?>" class="more-link">Conheça Mais &rsaquo;</a>
				</div>
			<?php
		}

		print $args[ 'after_body' ];
		print $args[ 'after_widget' ];
	}

	/**
	 * update data
	 *
	 * @name    update
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-30
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
	 * @since   2012-03-30
	 * @updated 2012-05-17
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
	 * @name    SAv_Widget_Candidato
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-05-17
	 * @updated 2012-05-17
	 * @return  void
	 */
	function SAv_Widget_Candidatos()
	{
		// register widget
		$this->WP_Widget( 'candidatos', 'SAv: Candidatos' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "SAv_Widget_Candidatos" );' ) );

?>
