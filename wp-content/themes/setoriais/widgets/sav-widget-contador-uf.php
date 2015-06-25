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

class SAv_Widget_Contador_UF extends WP_Widget
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $path = '';

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * load widget
	 *
	 * @name    widget
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-07-16
	 * @updated 2012-07-05
	 * @param   array $args - widget structure
	 * @param   array $instance - widget data
	 * @return  void
	 */
	function widget( $args, $instance )
	{
		global $wpdb;

		//$eleitores  = get_users( 'meta_key=candidatura&meta_value=candidatura&meta_compare=!=' );
		//$candidatos = get_users( 'meta_key=candidatura&meta_value=candidatura' );

		// show tags
		print $args[ 'before_widget' ];

		print $args[ 'before_head' ];
		print $args[ 'before_title' ] . 'Inscrições por estado'. $args[ 'after_title' ];
		print $args[ 'after_head' ];

		print $args[ 'before_body' ];

		$states	= array(
			''   => 'Não Informado',
			'AC' => 'Acre',
			'AL' => 'Alagoas',
			'AM' => 'Amazonas',
			'AP' => 'Amapá',
			'BA' => 'Bahia',
			'CE' => 'Ceará',
			'DF' => 'Distrito Federal',
			'ES' => 'Espírito Santo',
			'GO' => 'Goiás',
			'MA' => 'Maranhão',
			'MG' => 'Minas Gerais',
			'MS' => 'Mato Grosso do Sul',
			'MT' => 'Mato Grosso',
			'PA' => 'Pará',
			'PB' => 'Paraíba',
			'PE' => 'Pernambuco',
			'PI' => 'Piauí',
			'PR' => 'Paraná',
			'RJ' => 'Rio de Janeiro',
			'RN' => 'Rio Grande do Norte',
			'RO' => 'Rondônia',
			'RR' => 'Roraima',
			'RS' => 'Rio Grande do Sul',
			'SC' => 'Santa Catarina',
			'SE' => 'Sergipe',
			'SP' => 'São Paulo',
			'TO' => 'Tocantins',
			'EX' => 'Outro País'
		);

		// quantidade de inscritos por estado
		$meta       = $wpdb->get_blog_prefix() . 'capabilities';
		$inscritos  = $wpdb->get_col( $wpdb->prepare( "SELECT g.estado AS estado FROM {$wpdb->users} AS u LEFT JOIN {$wpdb->usermeta} AS um ON ( u.ID = um.user_id ) LEFT JOIN {$wpdb->cnpc_dados_geograficos} AS g ON ( u.user_login = g.login ) WHERE um.meta_key = %s", $meta ) );

		$quantidade_por_estado = array();
		$quantidade_por_regiao = array();

		foreach( $inscritos as $estado )
			$quantidade_por_estado[ $estado ] = $quantidade_por_estado[ $estado ] + 1;

		?>

		<div>
			<div style='float: left; clear: both; margin: 0px 0px 10px 50px;'>
				<?php foreach( $states as $key => $state ) : ?>
					<div>
						<p><?php print ( $quantidade_por_estado[ $key ] ) ? $quantidade_por_estado[ $key ] : 0; ?> <?php print $state; ?> </p>
					</div>
				<?php endforeach; ?>
			</div>
			<br class="clear" />
		</div>


		<?php

		print $args[ 'after_body' ];
		print $args[ 'after_widget' ];
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    SAv_Widget_Contador_UF
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-07-05
	 * @updated 2012-07-05
	 * @return  void
	 */
	function SAv_Widget_Contador_UF()
	{
		// register widget
		$this->WP_Widget( 'contador_estado', 'SAV: Contador Estado' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "SAv_Widget_Contador_UF" );' ) );

?>
