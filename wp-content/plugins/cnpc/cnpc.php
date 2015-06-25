<?php

/**
 * Copyright (c) 2012 Ministério da Cultura
 *
 * Written by
 *  Cleber Santos <cleber.santos@cultura.gov.br>
 *  Jaqueline Teles <jaquemteles@gmail.com>
 *  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
 *  Ricardo Evangelista <ricardo.evangelista@cultura.gov.br>
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
 * Plugin Name: CNPC
 * Plugin URI: http://xemele.cultura.gov.br/
 * Description: Cadastro de Eleitores e Candidatos para os Foruns Setoriais de Cultura
 * Author: SAv | Ministério da Cultura
 * Version: 2012.05.10
 * Author URI: http://marcelomesquita.com/
 */

//header('Location:http://cnpc.cultura.gov.br');

class CNPC
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////
	var $slug  = 'cnpc';
	var $dir   = '';
	var $url   = '';
	var $error = array();

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * update error messages
	 *
	 * @name    update_error
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-21
	 * @updated 2012-03-21
	 * @return  void
	 */
	function update_error( $error )
	{
		if( empty( $error ) )
			return false;

		if( is_array( $error ) )
			$this->error = array_merge( $this->error, $error );
		else
			array_push( $this->error, $error );
	}

	/**
	 * get error messages
	 *
	 * @name    get_error
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-21
	 * @updated 2012-03-22
	 * @return  mixed
	 */
	function get_error()
	{
		return $this->error;
	}

	/**
	 * check for error messages
	 *
	 * @name    have_error
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-21
	 * @updated 2012-03-22
	 * @return  void
	 */
	function have_error()
	{
		if( empty( $this->error ) )
			return false;
		else
			return true;
	}

	/**
	 * show error messages
	 *
	 * @name    have_error
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-22
	 * @updated 2012-05-08
	 * @return  string
	 */
	function show_error()
	{
		if( !$this->have_error() )
			return false;

		$output .= '<div class="error">';
		$output .= '<p><strong>Atenção:</strong></p>';
		$output .= '<ol>';

		foreach( $this->error as $error )
			$output .= "<li>{$error}</li>";

		$output .= '</ol>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * add tables to $wpdb
	 *
	 * @name    tables
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-05-07
	 * @return  void
	 */
	function tables()
	{
		global $wpdb;

		// eleitores/candidatos
		$wpdb->cnpc_dados_pessoais      = "{$wpdb->base_prefix}cnpc_dados_pessoais";
		$wpdb->cnpc_dados_profissionais = "{$wpdb->base_prefix}cnpc_dados_profissionais";
		$wpdb->cnpc_dados_geograficos   = "{$wpdb->base_prefix}cnpc_dados_geograficos";
		$wpdb->cnpc_dados_candidatura   = "{$wpdb->base_prefix}cnpc_dados_candidatura";

		// eleição
		$wpdb->cnpc_eleicao             = "{$wpdb->base_prefix}cnpc_eleicao";
	}

	/**
	 * add profiles, tables and initialize options
	 *
	 * @name    install
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-03-19
	 * @return  void
	 */
	function install()
	{
		$this->install_tables();
		$this->install_roles_privileges();
	}

	/**
	 * install tables
	 *
	 * @name    install_tables
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-05-22
	 * @return  void
	 */
	function install_tables()
	{
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// dados pessoais
		if( $wpdb->cnpc_dados_pessoais !== $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->cnpc_dados_pessoais}'" ) )
		{
			dbDelta( "
			CREATE TABLE {$wpdb->cnpc_dados_pessoais}
			(
				login				  VARCHAR( 60 ) NOT NULL,
				nome					VARCHAR( 250 ) NOT NULL,
				apelido       VARCHAR( 250 ) NULL,
				nascimento		DATETIME DEFAULT '0000-00-00' NULL,
				nacionalidade VARCHAR( 250 ) NULL,
				naturalidade	VARCHAR( 250 ) NULL,
				etnia         VARCHAR( 250 ) NULL,
				rg						BIGINT NULL,

				PRIMARY KEY( login )
			)
			" );
		}

		// dados profissionais
		if( $wpdb->cnpc_dados_profissionais !== $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->cnpc_dados_profissionais}'" ) )
		{
			dbDelta( "
				CREATE TABLE {$wpdb->cnpc_dados_profissionais}
				(
					login     VARCHAR( 60 ) NOT NULL,
					formacao  VARCHAR( 250 ) NULL,
					atuacao   VARCHAR( 250 ) NULL,
					biografia TEXT NULL,

					PRIMARY KEY( login )
				)
			" );
		}

		// dados geograficos
		if( $wpdb->cnpc_dados_geograficos !== $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->cnpc_dados_geograficos}'" ) )
		{
			dbDelta( "
				CREATE TABLE {$wpdb->cnpc_dados_geograficos}
				(
					login       VARCHAR( 60 ) NOT NULL,
					pais        VARCHAR( 250 ) NULL,
					estado      VARCHAR( 250 ) NULL,
					cidade      VARCHAR( 250 ) NULL,
					bairro      VARCHAR( 250 ) NULL,
					endereco    VARCHAR( 250 ) NULL,
					complemento VARCHAR( 250 ) NULL,
					cep         BIGINT NULL,

					PRIMARY KEY( login )
				)
			" );
		}

		// dados do candidato
		if( $wpdb->cnpc_dados_candidatura !== $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->cnpc_dados_candidatura}'" ) )
		{
			dbDelta( "
				CREATE TABLE {$wpdb->cnpc_dados_candidatura}
				(
					login      VARCHAR( 60 ) NOT NULL,
					propostas  TEXT NOT NULL,
					curriculo  VARCHAR( 250 ) NOT NULL,
					portfolio  VARCHAR( 250 ) NOT NULL,
					apoio      VARCHAR( 250 ) NOT NULL,
					registrado DATETIME DEFAULT '0000-00-00' NULL,
					atualizado DATETIME DEFAULT '0000-00-00' NULL,

					PRIMARY KEY( login )
				)
			" );
		}

		// eleição
		if( $wpdb->cnpc_eleicao !== $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->cnpc_eleicao}'" ) )
		{
			dbDelta( "
				CREATE TABLE {$wpdb->cnpc_eleicao}
				(
					id_eleicao   BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
					id_eleitor   BIGINT( 20 ) NOT NULL,
					id_candidato BIGINT( 20 ) NOT NULL,
					registrado   DATETIME DEFAULT '0000-00-00' NULL,

					PRIMARY KEY( id_eleicao )
				)
			" );
		}
	}


	/**
	 * install roles and privileges
	 *
	 * @name    install_roles_privileges
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-03-26
	 * @return  void
	 */
	function install_roles_privileges()
	{
		// create specific roles
		// participante
		add_role(
			'participante',
			'Participante',
			array(
				'read'                   => 1,
				'edit_posts'             => 0,
				'delete_posts'           => 0,
				'upload_files'           => 0,
				'publish_posts'          => 0,
				'edit_published_posts'   => 0,
				'delete_published_posts' => 0,
				'cnpc'                   => 1,
				'cnpc_vote'              => 0,
				'cnpc_approve_user'      => 0,
				'level_0'                => 1,
				'level_1'                => 1,
				'level_2'                => 1,
			)
		);

		// eleitor
		add_role(
			'eleitor',
			'Eleitor',
			array(
				'read'                   => 1,
				'edit_posts'             => 0,
				'delete_posts'           => 0,
				'upload_files'           => 0,
				'publish_posts'          => 0,
				'edit_published_posts'   => 0,
				'delete_published_posts' => 0,
				'cnpc'                   => 1,
				'cnpc_vote'              => 1,
				'cnpc_approve_user'      => 0,
				'level_0'                => 1,
				'level_1'                => 1,
				'level_2'                => 1,
			)
		);

		// candidato
		add_role(
			'candidato',
			'Candidato',
			array(
				'read'                   => 1,
				'edit_posts'             => 1,
				'delete_posts'           => 1,
				'upload_files'           => 1,
				'publish_posts'          => 1,
				'edit_published_posts'   => 1,
				'delete_published_posts' => 1,
				'cnpc'                   => 1,
				'cnpc_vote'              => 0,
				'cnpc_approve_user'      => 0,
				'level_0'                => 1,
				'level_1'                => 1,
				'level_2'                => 1,
			)
		);

		// candidato
		add_role(
			'comissao',
			'Comissao',
			array(
				'read'                   => 1,
				'edit_posts'             => 1,
				'delete_posts'           => 1,
				'upload_files'           => 1,
				'publish_posts'          => 1,
				'edit_published_posts'   => 1,
				'delete_published_posts' => 1,
				'cnpc'                   => 1,
				'cnpc_vote'              => 0,
				'cnpc_approve_user'      => 1,
				'level_0'                => 1,
				'level_1'                => 1,
				'level_2'                => 1,
			)
		);
	}

	/**
	 * delete roles
	 *
	 * @name    uninstall
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-03-19
	 * @updated 2012-03-19
	 * @return  void
	 */
	function uninstall()
	{
		global $wpdb;

		$wpdb->query( "DROP TABLES {$wpdb->cnpc_dados_pessoais}, {$wpdb->cnpc_dados_profissionais}, {$wpdb->cnpc_dados_geograficos}, {$wpdb->cnpc_dados_candidatura}, {$wpdb->cnpc_eleicao}" );

		remove_role( 'participante' );
		remove_role( 'eleitor' );
		remove_role( 'candidato' );
	}

	/**
	 * update participante
	 *
	 * @name    update_participante
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-05-07
	 * @return  void
	 */
	function update_participante( $user )
	{
		global $wpdb;

		if( !empty( $user[ 'pessoal' ] ) )
		{
			$user[ 'pessoal' ][ 'login' ] = $user[ 'login' ];
			$this->update_dados_pessoais( $user[ 'pessoal' ] );
		}

		if( !empty( $user[ 'profissional' ] ) )
		{
			$user[ 'profissional' ][ 'login' ] = $user[ 'login' ];
			$this->update_dados_profissionais( $user[ 'profissional' ] );
		}

		if( !empty( $user[ 'geografico' ] ) )
		{
			$user[ 'geografico' ][ 'login' ] = $user[ 'login' ];
			$this->update_dados_geograficos( $user[ 'geografico' ] );
		}

		if( !empty( $user[ 'candidato' ] ) )
		{
			$user[ 'candidato' ][ 'login' ] = $user[ 'login' ];
			$this->update_dados_contato( $user[ 'candidato' ] );
		}
	}

	/**
	 * get participante
	 *
	 * @name    get_participante
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-05-07
	 * @return  mixed
	 */
	function get_participante( $login )
	{
		$pessoal      = $this->get_dados_pessoais( $login );
		$profissional = $this->get_dados_profissionais( $login );
		$geografico   = $this->get_dados_geograficos( $login );
		$candidato    = $this->get_dados_candidatura( $login );

		$user = array_merge( $empresa, $pessoal, $profissional, $geografico, $candidato );

		return $user;
	}

	/**
	 * update dados pessoais
	 *
	 * @name    update_dados_pessoais
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-05-07
	 * @return  void
	 */
	function update_dados_pessoais( $user )
	{
		global $wpdb;

		$login = $wpdb->get_var( $wpdb->prepare( "SELECT login FROM {$wpdb->cnpc_dados_pessoais} WHERE login = %s LIMIT 1", $user[ 'login' ] ) );

		if( empty( $login ) )
		{
			return $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->cnpc_dados_pessoais} ( login, nome, apelido, nascimento, nacionalidade, naturalidade, etnia, rg ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %d )", $user[ 'login' ], $user[ 'nome' ], $user[ 'apelido' ], $user[ 'nascimento' ], $user[ 'nacionalidade' ], $user[ 'naturalidade' ], $user[ 'etnia' ], $user[ 'rg' ] ) );
		}
		else
		{
			$current_user = $this->get_dados_pessoais( $login );

			// atualizar apenas os dados informados
			if( !empty( $current_user ) )
				$user = array_merge( $current_user, $user );

			return $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->cnpc_dados_pessoais} SET nome = %s, apelido = %s, nascimento = %s, nacionalidade = %s, naturalidade = %s, etnia = %s, rg = %d WHERE login = %s", $user[ 'nome' ], $user[ 'apelido' ], $user[ 'nascimento' ], $user[ 'nacionalidade' ], $user[ 'naturalidade' ], $user[ 'etnia' ], $user[ 'rg' ], $user[ 'login' ] ) );
		}
	}

	/**
	 * get dados pessoais
	 *
	 * @name    get_dados_pessoais
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-03-19
	 * @return  mixed
	 */
	function get_dados_pessoais( $login )
	{
		global $wpdb;

		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->cnpc_dados_pessoais} WHERE login = %s LIMIT 1", $login ) );

		// transformar o objeto em array

		if( !empty( $user) )
			$user = get_object_vars( $user );

		return $user;
	}

	/**
	 * update dados profissionais
	 *
	 * @name    update_dados_profissionais
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-05-07
	 * @return  void
	 */
	function update_dados_profissionais( $user )
	{
		global $wpdb;

		$login = $wpdb->get_var( $wpdb->prepare( "SELECT login FROM {$wpdb->cnpc_dados_profissionais} WHERE login = %s LIMIT 1", $user[ 'login' ] ) );

		if( empty( $login ) )
		{
			return $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->cnpc_dados_profissionais} ( login, formacao, atuacao, biografia ) VALUES ( %s, %s, %s, %s )", $user[ 'login' ], $user[ 'formacao' ], $user[ 'atuacao' ], $user[ 'biografia' ] ) );
		}
		else
		{
			$current_user = $this->get_dados_profissionais( $login );

			// atualizar apenas os dados informados
			if( !empty( $current_user ) )
				$user = array_merge( $current_user, $user );

			return $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->cnpc_dados_profissionais} SET formacao = %s, atuacao = %s, biografia = %s WHERE login = %s", $user[ 'formacao' ], $user[ 'atuacao' ], $user[ 'biografia' ], $user[ 'login' ] ) );
		}
	}

	/**
	 * get dados profissionais
	 *
	 * @name    get_dados_profissionais
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-03-19
	 * @return  mixed
	 */
	function get_dados_profissionais( $login )
	{
		global $wpdb;

		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->cnpc_dados_profissionais} WHERE login = %s LIMIT 1", $login ) );
		
		// transformar o objeto em array
		if( !empty( $user) )
			$user = get_object_vars( $user );

		return $user;
	}

	/**
	 * update dados geograficos
	 *
	 * @name    update_dados_geograficos
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-05-08
	 * @return  void
	 */
	function update_dados_geograficos( $user )
	{
		global $wpdb;

		$login = $wpdb->get_var( $wpdb->prepare( "SELECT login FROM {$wpdb->cnpc_dados_geograficos} WHERE login = %s LIMIT 1", $user[ 'login' ] ) );

		if( empty( $login ) )
		{
			return $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->cnpc_dados_geograficos} ( login, pais, estado, cidade, bairro, endereco, complemento, cep ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %d )", $user[ 'login' ], $user[ 'pais' ], $user[ 'estado' ], $user[ 'cidade' ], $user[ 'bairro' ], $user[ 'endereco' ], $user[ 'complemento' ], $user[ 'cep' ] ) );
		}
		else
		{
			$current_user = $this->get_dados_geograficos( $login );

			// atualizar apenas os dados informados
			if( !empty( $current_user ) )
				$user = array_merge( $current_user, $user );

			return $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->cnpc_dados_geograficos} SET pais = %s, estado = %s, cidade = %s, bairro = %s, endereco = %s, complemento = %s, cep = %d WHERE login = %s", $user[ 'pais' ], $user[ 'estado' ], $user[ 'cidade' ], $user[ 'bairro' ], $user[ 'endereco' ], $user[ 'complemento' ], $user[ 'cep' ], $user[ 'login' ] ) );
		}
	}

	/**
	 * get dados geograficos
	 *
	 * @name    get_dados_geograficos
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-03-19
	 * @return  mixed
	 */
	function get_dados_geograficos( $login )
	{
		global $wpdb;

		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->cnpc_dados_geograficos} WHERE login = %s LIMIT 1", $login ) );

		// transformar o objeto em array
		if( !empty( $user) )
			$user = get_object_vars( $user );

		return $user;
	}

	/**
	 * update dados candidatura
	 *
	 * @name    update_dados_candidatura
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-22
	 * @updated 2012-05-07
	 * @return  void
	 */
	function update_dados_candidatura( $user )
	{
		global $wpdb;

		$login = $wpdb->get_var( $wpdb->prepare( "SELECT login FROM {$wpdb->cnpc_dados_candidatura} WHERE login = %s LIMIT 1", $user[ 'login' ] ) );

		if( empty( $login ) )
		{
			return $wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->cnpc_dados_candidatura} ( login, propostas, curriculo, portfolio, apoio, registrado ) VALUES ( %s, %s, %s, %s, %s, %s )", $user[ 'login' ], $user[ 'propostas' ], $user[ 'curriculo' ], $user[ 'portfolio' ], $user[ 'apoio' ], date( 'Y-m-d H:i:s' ) ) );
		}
		else
		{
			$current_user = $this->get_dados_candidatura( $login );

			// atualizar apenas os dados informados
			if( !empty( $current_user ) )
				$user = array_merge( $current_user, $user );

			return $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->cnpc_dados_candidatura} SET propostas = %s, curriculo = %s, portfolio = %s, apoio = %s, atualizado = %s WHERE login = %s", $user[ 'propostas' ], $user[ 'curriculo' ], $user[ 'portfolio' ], $user[ 'apoio' ], date( 'Y-m-d H:i:s' ), $user[ 'login' ] ) );
		}
	}

	/**
	 * get dados candidatura
	 *
	 * @name    get_dados_candidatura
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-22
	 * @updated 2012-03-22
	 * @return  mixed
	 */
	function get_dados_candidatura( $login )
	{
		global $wpdb;

		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->cnpc_dados_candidatura} WHERE login = %s LIMIT 1", $login ) );
		
		//printf( "SELECT * FROM {$wpdb->cnpc_dados_candidatura} WHERE login = %s LIMIT 1", $login );

		// transformar o objeto em array
		if( !empty( $user ) )
			$user = get_object_vars( $user );

		return $user;
	}

	/**
	 * upload anexo
	 *
	 * @name    upload_anexo
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-03-21
	 * @return  mixed
	 */
	function upload_anexo( $key, $name = '', $size = 1100000 )
	{
		if( empty( $_FILES[ $key ][ 'tmp_name' ] ) )
			return false;

		// verificar se o arquivo está no formato correto
		if( 'application/pdf' != $_FILES[ $key ][ 'type' ] )
		{
			$this->update_error( "O arquivo {$name} deve ser no formato portable document file (.pdf)" );

			return false;
		}

		// verificar se o arquivo está no tamanho correto
		if( $size < $_FILES[ $key ][ 'size' ] )
		{
			$this->update_error( "O arquivo {$name} excedeu o tamanho limite" );

			return false;
		}

		$new_name = sanitize_title( $_FILES[ $key ][ 'name' ] );
		$new_name = str_replace( "-pdf", "-" . wp_generate_password( 5, false ) . ".pdf", $new_name );

		// fazer o upload do arquivo
		$anexo = wp_upload_bits( $new_name , null, file_get_contents( $_FILES[ $key ][ 'tmp_name' ] ) );

		if( empty( $anexo[ 'error' ] ) )
			return $anexo[ 'url' ];
		else
			$this->update_error( $anexo[ 'error' ] );

		return false;
	}

	/**
	 * check if the current user is pessoa fisica
	 *
	 * @name    is_pessoa_fisica
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-03-19
	 * @return  bool
	 */
	function is_pessoa_fisica( $login = null )
	{
		global $user_login;

		$Validator = new Validator();

		if( empty( $login ) )
			$login = $user_login;

		if( $Validator->validate( $login, 'login', 'required=1&cpf=1' ) )
			return true;

		return false;
	}

	/**
	 * check if the current user is pessoa juridica
	 *
	 * @name    is_pessoa_juridica
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-03-19
	 * @return  bool
	 */
	function is_pessoa_juridica( $login = null )
	{
		global $user_login;

		$Validator = new Validator();

		if( empty( $login ) )
			$login = $user_login;

		if( $Validator->validate( $login, 'login', 'required=1&cnpj=1' ) )
			return true;

		return false;
	}

	/**
	 * show a menu dropdown from the states
	 *
	 * @name    dropdown_states
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-05-17
	 * @return  string
	 */
	function dropdown_states( $name = 'states', $selected = null, $all = false, $extra = null )
	{
		$states	= array(
			''   => 'Selecione o Estado',
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

		$output	= "<select id='{$name}' name='{$name}' {$extra}>";

		if( $all )
			$output .= "<option value=''>Todos</option>";

		foreach( $states as $acronym => $state )
		{
			if( $acronym == $selected )
				$acronym = 'selected="selected"';

			$output .= "<option value='{$acronym}' {$acronym}>{$state}</option>";
		}

		$output .= "</select>";

		return $output;
	}

	/**
	 * show a menu dropdown from the segmentos
	 *
	 * @name    dropdown_segmentos
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-05-17
	 * @updated 2012-06-06
	 * @return  string
	 */
	function dropdown_segmentos( $setorial, $name = 'segmentos', $selected = null, $extra = null )
	{
		switch( $setorial )
		{
			case 'Fórum Nacional Setorial  Arquitetura e Urbanismo' :
				$segmentos	= array(
					'Arquitetura/projeto',
					'Planejamento urbano',
					'Patrimônio cultural',
					'Ensino/formação/teoria/pesquisa',
					'Paisagismo/conforto ambiental/sustentabilidade ambiental',
					'Associações reconhecidas de luta por moradia/AT ou atuantes nos segmentos culturais de influência da Arquitetura e Urbanismo',
					'Mídia/publicidade/publicações',
					'Conselho Nacional de Arquitetura (Conselho profissional)',
					'Tecnologia/sistemas construtivos',
					'Representação discente (FENEA)'
				);
				break;
			case 'Fórum Nacional Setorial  Arquivos' :
				$segmentos	= array(
					'Gestor',
					'Profissional',
					'Usuário'
				);
				break;
			case 'Fórum Nacional Setorial  Arte Digital' :
				$segmentos	= array(
					'Área técnico-artística',
					'Área teórico-crítica',
					'Área de patrimônio cultural'
				);
				break;
			case 'Fórum Nacional Setorial  Artes Visuais' :
				$segmentos	= array(
					'Área artística',
					'Área produtiva',
					'Área de mediação'
				);
				break;
			case 'Fórum Nacional Setorial  Artesanato' :
				$segmentos	= array(
					'Artesãos',
					'Área Institucional (representantes de grupos, cooperativas, associações, federações e sindicatos)',
					'Área Econômica (produtores e promotores de eventos do setor de artesanato)',
					'Área Acadêmica (estudiosos e pesquisadores da área)'
				);
				break;
			case 'Fórum Nacional Setorial  Circo' :
				$segmentos	= array(
					'Pesquisadores',
					'Artistas',
					'Circos de lonas pequenas',
					'Circos de lonas grandes',
					'Escolas',
					'Circos sociais',
					'Grupos e trupes',
				);
				break;
			case 'Fórum Nacional Setorial  Culturas dos Povos Indígenas' :
				$segmentos	= array(
					'Representantes indígenas',
					'Mediadores culturais'
				);
				break;
			case 'Fórum Nacional Setorial  Culturas Afro-Brasileiras' :
				$segmentos	= array(
					'povos tradicionais de terreiro',
					'comunidades quilombolas',
					'mestres',
					'expressões artístico-culturais',
					'patrimônio imaterial',
					'pesquisadores de cultura afro-brasileira'
				);
				break;
			case 'Fórum Nacional Setorial  Culturas Populares' :
				$segmentos	= array(
					'Mestres',
					'Fazedores de cultura',
					'Pesquisadores',
					'Mediadores'
				);
				break;
			case 'Fórum Nacional Setorial  Dança' :
				$segmentos	= array(
					'Área artística',
					'Área produtiva',
					'Área de formação'
				);
				break;
			case 'Fórum Nacional Setorial  Design' :
				$segmentos	= array(
					'Entidades profissionais (conselhos, sindicatos, institutos, associações e federações já constituídos)',
					'Entidades acadêmicas e de pesquisa (laboratórios de pesquisa, associações, núcleos de pesquisa, institutos, observatórios)',
					'Movimentos sociais e organizações não-governamentais'
				);
				break;
			case 'Fórum Nacional Setorial  Livro, Leitura e Literatura' :
				$segmentos	= array(
					'Produção e Distribuição',
					'Criação',
					'Mediação'
				);
				break;
			case 'Fórum Nacional Setorial  Moda' :
				$segmentos	= array(
					'Área artístico-criativa',
					'Área produtivo-comercial',
					'Área associativo-acadêmica'
				);
				break;
			case 'Fórum Nacional Setorial  Música' :
				$segmentos	= array(
					'Área artístico-criativa',
					'Área produtiva',
					'Área associativa (sem caráter econômico, considerando associações e entidades relacionadas ao setor da Música)'
				);
				break;
			case 'Fórum Nacional Setorial  Patrimônio Imaterial' :
				$segmentos	= array(
					'Área cultural',
					'Área produtiva',
					'Área de mediação'
				);
				break;
			case 'Fórum Nacional Setorial  Patrimônio Material' :
				$segmentos	= array(
					'pesquisadores da área de patrimônio material',
					'conservadores-restauradores',
					'consultores na área de gestão e produção, ligados à preservação do patrimônio'
				);
				break;
			case 'Fórum Nacional Setorial  Teatro' :
				$segmentos	= array(
					'Formação e memória',
					'Criação e pesquisa',
					'Produção e difusão'
				);
				break;
		}

		$output	= "<select id='{$name}' name='{$name}' {$extra}>";

		$output .= "<option value=''>Selecione o Segmento</option>";

		foreach( $segmentos as $acronym => $segmento )
		{
			if( $segmento == $selected )
				$output .= "<option value='{$segmento}' selected='selected'>{$segmento}</option>";
			else
				$output .= "<option value='{$segmento}'>{$segmento}</option>";
		}

		$output .= "</select>";

		return $output;
	}

	/**
	 * load cnpc styles to the theme
	 *
	 * @name    styles
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-20
	 * @updated 2012-03-20
	 * @return  void
	 */
	function frontend_styles()
	{
		wp_enqueue_style( 'cnpc', $this->url . '/css/cnpc.css' );
	}

	/**
	 * load cnpc scripts to the theme
	 *
	 * @name    scripts
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-20
	 * @updated 2012-03-20
	 * @return  void
	 */
	function frontend_scripts()
	{
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'cnpc', $this->url . '/js/cnpc.js', array( 'jquery' ) );
	}

	/**
	 * clear text
	 *
	 * @name    clear_text
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-03-19
	 * @updated 2012-03-21
	 * @return  string
	 */
	function clear_text( $text )
	{
		$text = htmlspecialchars( stripslashes( $text ), ENT_NOQUOTES );

		$text = str_replace( "\r\n", "\n", $text );
		$text = str_replace( "’", "'", $text );
		$text = str_replace( "‘", "'", $text );
		$text = str_replace( '”', '"', $text );
		$text = str_replace( '“', '"', $text );
		$text = str_replace( '–', '-', $text );
		$text = str_replace( '…', '.', $text );

		return $text;
	}

	/**
	 * show user actions
	 *
	 * @name    user_row_actions
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-03-26
	 * @updated 2012-03-26
	 * @return  array
	 */
	function user_row_actions( $actions, $user )
	{
		$user_page = get_author_posts_url( $user->ID, $user->user_nicename );

		$actions[ 'inscrição' ] = "<a href='users.php?page=cnpc_approve_user&user_id={$user->ID}'>Inscrição</a>";

		return $actions;
	}

	/**
	 * show user state
	 *
	 * @name    user_state_column
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-06-12
	 * @updated 2012-06-12
	 * @return  array
	 */
	function user_state_column( $columns )
	{
		$columns[ 'state' ] = 'Estado';

    return $columns;
	}

	/**
	 * show user state content
	 *
	 * @name    user_state_column_content
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-06-12
	 * @updated 2012-06-12
	 * @return  array
	 */
	function user_state_column_content( $value, $column_name, $user_id )
	{
		global $wpdb;

		$user = get_userdata( $user_id );

		$state = $wpdb->get_var( $wpdb->prepare( "SELECT estado FROM {$wpdb->cnpc_dados_geograficos} WHERE login = %s", $user->user_login ) );

		if( 'state' == $column_name )
			return $state;
	}

	/**
	 * create the administrative menus
	 *
	 * @name    menu
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-26
	 * @updated 2012-03-26
	 * @return  void
	 */
	function menus()
	{
		// add_submenu_page( $parent, $page_title, $menu_title, $access_level, $file, $function = '' )
		add_submenu_page( 'users.php', 'Inscrições', 'Incrições', 'cnpc_approve_user', 'cnpc_approve_user', array( &$this, 'show_user_inscription' ) );
	}

	/**
	 * show user inscription
	 *
	 * @name    show_user_inscription
	 * @author  Marcelo Mesquita <stallefish@gmail.com>
	 * @since   2012-03-26
	 * @updated 2012-05-15
	 * @return  array
	 */
	function show_user_inscription()
	{
		if( !current_user_can( 'cnpc_approve_user' ) )
			return false;

		$user_id = (int) isset( $_GET[ 'user_id' ] ) ? $_GET[ 'user_id' ] : "";

		if( empty( $user_id ) )
			return false;

		$user = get_userdata( $user_id );
				
		
		$dados_pessoais      = $this->get_dados_pessoais( $user->user_login );
		$dados_profissionais = $this->get_dados_profissionais( $user->user_login );
		$dados_geograficos   = $this->get_dados_geograficos( $user->user_login );
		$dados_candidatura   = $this->get_dados_candidatura( $user->user_login );
		
		
		if($_POST)
		{	
			// ========= CAMPOS DE AVALIAÇÃO GET FORMULARIO ================
			//Nome
			$observacaoNome		=  isset( $_POST['observacaoNome'] ) ? $_POST['observacaoNome'] : "" ;
			$avaliacaoNome		=  isset( $_POST['avaliacaoNome'] ) ? $_POST['avaliacaoNome'] : "";
			//Apelido
			$avaliacaoApelido	=  isset( $_POST['avaliacaoApelido'] ) ? $_POST['avaliacaoApelido'] : "";
			$observacaoApelido	=  isset( $_POST['observacaoApelido'] ) ? $_POST['observacaoApelido'] : "";		
			
			$avaliacaoCPF 				=  isset( $_POST['avaliacaoCPF'] ) ? $_POST['avaliacaoCPF'] : "";
			$observacaoCPF 				=  isset( $_POST['observacaoCPF'] ) ? $_POST['observacaoCPF'] : "";
			$avaliacaoRegistroGeral 	=  isset( $_POST['avaliacaoRegistroGeral'] ) ? $_POST['avaliacaoRegistroGeral'] : "";
			$observacaoRegistroGeral 	=  isset( $_POST['observacaoRegistroGeral'] ) ? $_POST['observacaoRegistroGeral'] : "";
			$avaliacaoNaturalidade 		=  isset( $_POST['avaliacaoNaturalidade'] ) ? $_POST['avaliacaoNaturalidade'] : "";
			$observacaoNaturalidade 	=  isset( $_POST['observacaoNaturalidade'] ) ? $_POST['observacaoNaturalidade'] : "";
			$avaliacaoEmail 			=  isset( $_POST['avaliacaoEmail'] ) ? $_POST['avaliacaoEmail'] : "";
			$observacaoEmail 			=  isset( $_POST['observacaoEmail'] ) ? $_POST['observacaoEmail'] : "";
			$avaliacaoEndereco 			=  isset( $_POST['avaliacaoEndereco'] ) ? $_POST['avaliacaoEndereco'] : "";
			$observacaoEndereco 		=  isset( $_POST['observacaoEndereco'] ) ? $_POST['observacaoEndereco'] : "";
			$avaliacaoComplemento 		=  isset( $_POST['avaliacaoComplemento'] ) ? $_POST['avaliacaoComplemento'] : "";
			$observacaoComplemento 		=  isset( $_POST['observacaoComplemento'] ) ? $_POST['observacaoComplemento'] : "";
			$avaliacaoBairro 			=  isset( $_POST['avaliacaoBairro'] ) ? $_POST['avaliacaoBairro'] : "";
			$observacaoBairro 			=  isset( $_POST['observacaoBairro'] ) ? $_POST['observacaoBairro'] : "";
			
			$avaliacaoCEP 						= isset( $_POST['avaliacaoCEP'] ) ? $_POST['avaliacaoCEP'] : "";
			$observacaoCEP 						= isset( $_POST['observacaoCEP']) ? $_POST['observacaoCEP'] : "";
			$avaliacaoCidade 					= isset( $_POST['avaliacaoCidade']) ? $_POST['avaliacaoCidade'] : "";
			$observacaoCidade 					= isset( $_POST['observacaoCidade']) ? $_POST['observacaoCidade'] : "";
			$avaliacaoEstado 					= isset( $_POST['avaliacaoEstado']) ? $_POST['avaliacaoEstado'] : "";
			$observacaoEstado 					= isset( $_POST['observacaoEstado']) ? $_POST['observacaoEstado'] : "";
			$avaliacaoFormacao 					= isset( $_POST['avaliacaoFormacao']) ? $_POST['avaliacaoFormacao'] : "";
			$observacaoFormacao 				= isset( $_POST['observacaoFormacao']) ? $_POST['observacaoFormacao'] : "";
			$avaliacaoAtuacao 					= isset( $_POST['avaliacaoAtuacao']) ? $_POST['avaliacaoAtuacao'] : "";
			$observacaoAtuacao 					= isset( $_POST['observacaoAtuacao']) ? $_POST['observacaoAtuacao'] : "";
			$avaliacaoApresentacao 				= isset( $_POST['avaliacaoApresentacao']) ? $_POST['avaliacaoApresentacao'] : "";
			$observacaoApresentacao 			= isset( $_POST['observacaoApresentacao']) ? $_POST['observacaoApresentacao'] : "";
			$avaliacaoSegmento 					= isset( $_POST['avaliacaoSegmento']) ? $_POST['avaliacaoSegmento'] : "";
			$observacaoSegmento 				= isset( $_POST['observacaoSegmento']) ? $_POST['observacaoSegmento'] : "";
			$avaliacaoComprovanteAtuacaoSetor 	= isset( $_POST['avaliacaoComprovanteAtuacaoSetor']) ? $_POST['avaliacaoComprovanteAtuacaoSetor'] : "";
			$observacaoComprovanteAtuacaoSetor 	= isset( $_POST['observacaoComprovanteAtuacaoSetor']) ? $_POST['observacaoComprovanteAtuacaoSetor'] : "";
			$avaliacaoComprovanteIdentidade 	= isset( $_POST['avaliacaoComprovanteIdentidade']) ? $_POST['avaliacaoComprovanteIdentidade'] : "";
			$observacaoComprovanteIdentidade 	= isset( $_POST['observacaoComprovanteIdentidade']) ? $_POST['observacaoComprovanteIdentidade'] : "";
			$avaliacaoComprovanteCPF 			= isset( $_POST['avaliacaoComprovanteCPF']) ? $_POST['avaliacaoComprovanteCPF'] : "";
			$observacaoComprovanteCPF 			= isset( $_POST['observacaoComprovanteCPF']) ? $_POST['observacaoComprovanteCPF'] : "";
			$avaliacaoComprovanteResidencia 	= isset( $_POST['avaliacaoComprovanteResidencia']) ? $_POST['avaliacaoComprovanteResidencia'] : "";
			$observacaoComprovanteResidencia 	= isset( $_POST['observacaoComprovanteResidencia']) ? $_POST['observacaoComprovanteResidencia'] : "";
			
				
			
			$avaliacaoCargo  						= isset( $_POST['avaliacaoCargo'] ) ? $_POST['avaliacaoCargo'] : "";
			$observacaoCargo  						= isset( $_POST['observacaoCargo'] ) ? $_POST['observacaoCargo']  : "";
			
			
			$avaliacaoComprovanteComissionado  		= isset( $_POST['avaliacaoComprovanteComissionado'] ) ? $_POST['avaliacaoComprovanteComissionado'] : "";
			$observacaoComprovanteComissionado  	= isset( $_POST['observacaoComprovanteComissionado'] ) ? $_POST['observacaoComprovanteComissionado'] : "";
			
			$avaliacaoNascimento  					= isset( $_POST['avaliacaoNascimento'] ) ? $_POST['avaliacaoNascimento'] : "";
			$observacaoNascimento  					= isset( $_POST['observacaoNascimento'] ) ? $_POST['observacaoNascimento'] : "";
			
			
			$avaliacaoPropostas  					= isset( $_POST['avaliacaoPropostas'] ) ? $_POST['avaliacaoPropostas'] : "";
			$observacaoPropostas  					= isset( $_POST['observacaoPropostas'] ) ? $_POST['observacaoPropostas'] : "";
			$avaliacaoComprovanteAtuacaoCultural  	= isset( $_POST['avaliacaoComprovanteAtuacaoCultural'] ) ? $_POST['avaliacaoComprovanteAtuacaoCultural'] : "";
			$observacaoComprovanteAtuacaoCultural	= isset( $_POST['observacaoComprovanteAtuacaoCultural'] ) ? $_POST['observacaoComprovanteAtuacaoCultural'] : "";
			$avaliacaoPortfolio  					= isset( $_POST['avaliacaoPortfolio'] ) ? $_POST['avaliacaoPortfolio'] : "";
			$observacaoComprovantePortfolio  		= isset( $_POST['observacaoComprovantePortfolio'] ) ? $_POST['observacaoComprovantePortfolio'] : "";
			$avaliacaoCartaApoio  					= isset( $_POST['avaliacaoCartaApoio'] ) ? $_POST['avaliacaoCartaApoio'] : "";
			$observacaoCartaApoio  					= isset( $_POST['observacaoCartaApoio'] ) ? $_POST['observacaoCartaApoio'] : "";
			
			$avaliacaoEleitor  						= isset( $_POST['avaliacaoEleitor'] ) ? $_POST['avaliacaoEleitor'] : "";
			$observacaoEleitor  					= isset( $_POST['observacaoEleitor'] ) ? $_POST['observacaoEleitor'] : "";
			
			$avaliacaoCandidato  					= isset( $_POST['avaliacaoCandidato'] ) ? $_POST['avaliacaoCandidato'] : "";
			$observacaoCandidato  					= isset( $_POST['observacaoCandidato'] ) ? $_POST['observacaoCandidato'] : "";
			
			$avaliacaoInscrito  					= isset( $_POST['avaliacaoInscrito'] ) ? $_POST['avaliacaoInscrito'] : "";
			$observacaoInscrito  					= isset( $_POST['observacaoInscrito'] ) ? $_POST['observacaoInscrito'] : "";
			
					
			
			// ========= CAMPOS DE AVALIAÇÃO PERSISTENCIA BANCO ================	
			
			update_user_meta(  $user_id, 'avaliacaoNome', $avaliacaoNome);			
			update_user_meta( $user_id, 'observacaoNome', $observacaoNome);
			
			update_user_meta( $user_id, 'avaliacaoApelido', $avaliacaoApelido);			
			update_user_meta( $user_id, 'observacaoApelido', $observacaoApelido);
			
			update_user_meta( $user_id, 'avaliacaoCPF', $avaliacaoCPF);			
			update_user_meta( $user_id, 'observacaoCPF', $observacaoCPF);
			
			update_user_meta( $user_id, 'avaliacaoRegistroGeral', $avaliacaoRegistroGeral);			
			update_user_meta( $user_id, 'observacaoRegistroGeral', $observacaoRegistroGeral);
			
			update_user_meta( $user_id, 'avaliacaoNaturalidade', $avaliacaoNaturalidade);			
			update_user_meta( $user_id, 'observacaoNaturalidade', $observacaoNaturalidade);
			
			update_user_meta( $user_id, 'avaliacaoEmail', $avaliacaoEmail);			
			update_user_meta( $user_id, 'observacaoEmail', $observacaoEmail);
			
			update_user_meta( $user_id, 'avaliacaoEndereco', $avaliacaoEndereco);			
			update_user_meta( $user_id, 'observacaoEndereco', $observacaoEndereco);
			
			update_user_meta( $user_id, 'avaliacaoComplemento', $avaliacaoComplemento);			
			update_user_meta( $user_id, 'observacaoComplemento', $observacaoComplemento);
			
			update_user_meta( $user_id, 'avaliacaoBairro', $avaliacaoBairro);			
			update_user_meta( $user_id, 'observacaoBairro', $observacaoBairro);
			
			update_user_meta( $user_id, 'avaliacaoCEP', $avaliacaoCEP);			
			update_user_meta( $user_id, 'observacaoCEP', $observacaoCEP);
			
			update_user_meta( $user_id, 'avaliacaoCidade', $avaliacaoCidade);			
			update_user_meta( $user_id, 'observacaoCidade', $observacaoCidade);
			
			update_user_meta( $user_id, 'avaliacaoEstado', $avaliacaoEstado);			
			update_user_meta( $user_id, 'observacaoEstado', $observacaoEstado);
			
			update_user_meta( $user_id, 'avaliacaoFormacao', $avaliacaoFormacao);			
			update_user_meta( $user_id, 'observacaoFormacao', $observacaoFormacao);
			
			update_user_meta( $user_id, 'avaliacaoAtuacao', $avaliacaoAtuacao);			
			update_user_meta( $user_id, 'observacaoAtuacao', $observacaoAtuacao);
			
			update_user_meta( $user_id, 'avaliacaoApresentacao', $avaliacaoApresentacao);			
			update_user_meta( $user_id, 'observacaoApresentacao', $observacaoApresentacao);
			
			update_user_meta( $user_id, 'avaliacaoComprovanteAtuacaoSetor', $avaliacaoComprovanteAtuacaoSetor);			
			update_user_meta( $user_id, 'observacaoComprovanteAtuacaoSetor', $observacaoComprovanteAtuacaoSetor);
			
			update_user_meta( $user_id, 'avaliacaoComprovanteIdentidade', $avaliacaoComprovanteIdentidade);			
			update_user_meta( $user_id, 'observacaoComprovanteIdentidade', $observacaoComprovanteIdentidade);
			
			update_user_meta( $user_id, 'avaliacaoComprovanteCPF', $avaliacaoComprovanteCPF);			
			update_user_meta( $user_id, 'observacaoComprovanteCPF', $observacaoComprovanteCPF);
			
			update_user_meta( $user_id, 'avaliacaoComprovanteResidencia', $avaliacaoComprovanteResidencia);			
			update_user_meta( $user_id, 'observacaoComprovanteResidencia', $observacaoComprovanteResidencia);
			
			update_user_meta( $user_id, 'avaliacaoCargo', $avaliacaoCargo);			
			update_user_meta( $user_id, 'observacaoCargo', $observacaoCargo);
			
			update_user_meta( $user_id, 'avaliacaoComprovanteComissionado', $avaliacaoComprovanteComissionado);			
			update_user_meta( $user_id, 'observacaoComprovanteComissionado', $observacaoComprovanteComissionado);	

			update_user_meta( $user_id, 'avaliacaoNascimento', $avaliacaoNascimento);			
			update_user_meta( $user_id, 'observacaoNascimento', $observacaoNascimento);
			
			update_user_meta( $user_id, 'avaliacaoPropostas', $avaliacaoPropostas);			
			update_user_meta( $user_id, 'observacaoPropostas', $observacaoPropostas);
			
			update_user_meta( $user_id, 'avaliacaoComprovanteAtuacaoCultural', $avaliacaoComprovanteAtuacaoCultural);			
			update_user_meta( $user_id, 'observacaoComprovanteAtuacaoCultural', $observacaoComprovanteAtuacaoCultural);
			
			update_user_meta( $user_id, 'avaliacaoPortfolio', $avaliacaoPortfolio);			
			update_user_meta( $user_id, 'observacaoComprovantePortfolio', $observacaoComprovantePortfolio);
			
			update_user_meta( $user_id, 'avaliacaoCartaApoio', $avaliacaoCartaApoio);			
			update_user_meta( $user_id, 'observacaoCartaApoio', $observacaoCartaApoio);	
			
			update_user_meta( $user_id, 'avaliacaoEleitor', $avaliacaoEleitor);			
			update_user_meta( $user_id, 'observacaoEleitor', $observacaoEleitor);	
			
			update_user_meta( $user_id, 'avaliacaoCandidato', $avaliacaoCandidato);			
			update_user_meta( $user_id, 'observacaoCandidato', $observacaoCandidato);	
			
			update_user_meta( $user_id, 'avaliacaoInscrito', $avaliacaoInscrito);			
			update_user_meta( $user_id, 'observacaoIinscrito', $observacaoIinscrito);	
		}
				
		
		// PEGA DADOS DO BANCO		
		$avaliacaoNome 							= get_user_meta( $user_id, 'avaliacaoNome', true);			
		$observacaoNome 						= get_user_meta( $user_id, 'observacaoNome', true);

		$avaliacaoApelido 						= get_user_meta( $user_id, 'avaliacaoApelido', true);			
		$observacaoApelido 						= get_user_meta( $user_id, 'observacaoApelido', true);

		$avaliacaoCPF 							= get_user_meta( $user_id, 'avaliacaoCPF', true);			
		$observacaoCPF 							= get_user_meta( $user_id, 'observacaoCPF', true);

		$avaliacaoRegistroGeral 				= get_user_meta( $user_id, 'avaliacaoRegistroGeral', true);			
		$observacaoRegistroGeral 				= get_user_meta( $user_id, 'observacaoRegistroGeral', true);

		$avaliacaoNaturalidade					= get_user_meta( $user_id, 'avaliacaoNaturalidade', true);			
		$observacaoNaturalidade 				= get_user_meta( $user_id, 'observacaoNaturalidade', true);

		$avaliacaoEmail 						= get_user_meta( $user_id, 'avaliacaoEmail', true);			
		$observacaoEmail 						= get_user_meta( $user_id, 'observacaoEmail', true);

		$avaliacaoEndereco 						= get_user_meta( $user_id, 'avaliacaoEndereco', true);			
		$observacaoEndereco 					= get_user_meta( $user_id, 'observacaoEndereco', true);

		$avaliacaoComplemento 					= get_user_meta( $user_id, 'avaliacaoComplemento', true);			
		$observacaoComplemento 					= get_user_meta( $user_id, 'observacaoComplemento', true);

		$avaliacaoBairro 						= get_user_meta( $user_id, 'avaliacaoBairro', true);			
		$observacaoBairro 						= get_user_meta( $user_id, 'observacaoBairro', true);

		$avaliacaoCEP 							= get_user_meta( $user_id, 'avaliacaoCEP', true);			
		$observacaoCEP 							= get_user_meta( $user_id, 'observacaoCEP', true);

		$avaliacaoCidade 						= get_user_meta( $user_id, 'avaliacaoCidade', true);			
		$observacaoCidade 						= get_user_meta( $user_id, 'observacaoCidade', true);

		$avaliacaoEstado 						= get_user_meta( $user_id, 'avaliacaoEstado', true);			
		$observacaoEstado 						= get_user_meta( $user_id, 'observacaoEstado', true);

		$avaliacaoFormacao 						= get_user_meta( $user_id, 'avaliacaoFormacao', true);			
		$observacaoFormacao 					= get_user_meta( $user_id, 'observacaoFormacao', true);

		$avaliacaoAtuacao 						= get_user_meta( $user_id, 'avaliacaoAtuacao', true);			
		$observacaoAtuacao 						= get_user_meta( $user_id, 'observacaoAtuacao', true);

		$avaliacaoApresentacao 					= get_user_meta( $user_id, 'avaliacaoApresentacao', true);			
		$observacaoApresentacao 				= get_user_meta( $user_id, 'observacaoApresentacao', true);

		$avaliacaoSegmento 						= get_user_meta( $user_id, 'avaliacaoSegmento', true);
		$observacaoSegmento 					= get_user_meta( $user_id,'observacaoSegmento', true);

		$avaliacaoComprovanteAtuacaoSetor 		= get_user_meta( $user_id, 'avaliacaoComprovanteAtuacaoSetor', true);			
		$observacaoComprovanteAtuacaoSetor 		= get_user_meta( $user_id, 'observacaoComprovanteAtuacaoSetor', true);

		$avaliacaoComprovanteIdentidade 		= get_user_meta( $user_id, 'avaliacaoComprovanteIdentidade', true);			
		$observacaoComprovanteIdentidade 		= get_user_meta( $user_id, 'observacaoComprovanteIdentidade', true);

		$avaliacaoComprovanteCPF 				= get_user_meta( $user_id, 'avaliacaoComprovanteCPF', true);			
		$observacaoComprovanteCPF 				= get_user_meta( $user_id, 'observacaoComprovanteCPF', true);

		$avaliacaoComprovanteResidencia 		= get_user_meta( $user_id, 'avaliacaoComprovanteResidencia', true);			
		$observacaoComprovanteResidencia 		= get_user_meta( $user_id, 'observacaoComprovanteResidencia', true);		
		
		$avaliacaoCargo 						= get_user_meta( $user_id, 'avaliacaoCargo', true);			
		$observacaoCargo 						= get_user_meta( $user_id, 'observacaoCargo', true);
		
		$avaliacaoComprovanteComissionado 		= get_user_meta( $user_id, 'avaliacaoComprovanteComissionado', true);			
		$observacaoComprovanteComissionado 		= get_user_meta( $user_id, 'observacaoComprovanteComissionado', true);
		
		$avaliacaoNascimento 					= get_user_meta( $user_id, 'avaliacaoNascimento', true);			
		$observacaoNascimento 					= get_user_meta( $user_id, 'observacaoNascimento', true);
		
		
		$avaliacaoPropostas 					= get_user_meta( $user_id, 'avaliacaoPropostas', true);			
		$observacaoPropostas 					= get_user_meta( $user_id, 'observacaoPropostas', true);

		$avaliacaoComprovanteAtuacaoCultural 	= get_user_meta( $user_id, 'avaliacaoComprovanteAtuacaoCultural', true);			
		$observacaoComprovanteAtuacaoCultural	= get_user_meta( $user_id, 'observacaoComprovanteAtuacaoCultural', true);

		$avaliacaoPortfolio 					= get_user_meta( $user_id, 'avaliacaoPortfolio', true);			
		$observacaoComprovantePortfolio 		= get_user_meta( $user_id, 'observacaoComprovantePortfolio', true);

		$avaliacaoCartaApoio 					= get_user_meta( $user_id, 'avaliacaoCartaApoio', true);			
		$observacaoCartaApoio 					= get_user_meta( $user_id, 'observacaoCartaApoio', true);	
		
		$avaliacaoEleitor 						= get_user_meta( $user_id, 'avaliacaoEleitor', true);			
		$observacaoEleitor 						= get_user_meta( $user_id, 'observacaoEleitor', true);	
		
		$avaliacaoCandidato 					= get_user_meta( $user_id, 'avaliacaoCandidato', true);			
		$observacaoCandidato 					= get_user_meta( $user_id, 'observacaoCandidato', true);	
		
		$avaliacaoInscrito 						= get_user_meta( $user_id, 'avaliacaoInscrito', true);			
		$observacaoInscrito 					= get_user_meta( $user_id, 'observacaoInscrito', true);	
					
		$observacoesAntigas 					= get_user_meta( $user_id, 'observacoes', true);

		define('OBSERVACOES_ANTIGAS', $observacoesAntigas);
		
		
		
		/*
		echo "<pre>";
		print_r($dados_profissionais);
		echo "</pre>"
		*/
		
		?>
			<style type="text/css">
				#profile-page .form-table textarea {
					width: 200px;
				}
			</style>
			<form name="camposAvaliacao" method="post">
			<div class="wrap" id="profile-page">
				<div id="icon-users" class="icon32"><br /></div>
				<h2>Inscrições</h2>
				
				<h3>Dados Pessoais</h3>
				<table class="form-table">

					<tr>
						<th><label for="display_name">Nome</label></th>
						<td>
							<input type="text" name="nome" id="nome" value="<?php print $dados_pessoais[ 'nome' ]; ?>" class="regular-text" disabled="disabled" />
						</td>
						<td >
							<input <?php if($avaliacaoNome == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoNome"> sim
							<input <?php if($avaliacaoNome == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoNome"> não
							<input <?php if($avaliacaoNome == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoNome"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoNome"><?php echo $observacaoNome;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for='apelido'>Nome Artístico / Apelido</label></th>
						<td>
							<input type="text" id="apelido" name="apelido" value="<?php print $dados_pessoais[ 'apelido' ]; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoApelido == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoApelido"> sim
							<input <?php if($avaliacaoApelido == 2) echo "checked = checked"; ?> value="0"type="radio" name="avaliacaoApelido"> não
							<input <?php if($avaliacaoApelido == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoApelido"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoApelido"><?php echo $observacaoApelido;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="cpf">CPF</label></th>
						<td>
							<input type="text" name="cpf" id="cpf" value="<?php print $dados_pessoais[ 'login' ]; ?>" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoCPF == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoCPF"> sim
							<input <?php if($avaliacaoCPF == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoCPF"> não
							<input <?php if($avaliacaoCPF == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoCPF"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoCPF"><?php echo $observacaoCPF;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="rg">Registro Geral</label></th>
						<td>
							<input type="text" name="rg" id="rg" value="<?php print $dados_pessoais[ 'rg' ]; ?>" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoRegistroGeral == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoRegistroGeral"> sim
							<input <?php if($avaliacaoRegistroGeral == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoRegistroGeral"> não
							<input <?php if($avaliacaoRegistroGeral == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoRegistroGeral"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoRegistroGeral"><?php echo $observacaoRegistroGeral;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="nascimento">Nascimento</label></th>
						<td>
							<input type="text" name="nascimento" id="nascimento" value="<?php print date( 'd\/m\/Y', strtotime( $dados_pessoais[ 'nascimento' ] ) ); ?>" disabled="disabled" /><br />
						</td>						
						<td >
							<input <?php if($avaliacaoNascimento == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoNascimento"> sim
							<input <?php if($avaliacaoNascimento == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoNascimento"> não
							<input <?php if($avaliacaoNascimento == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoNascimento"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoNascimento"><?php echo $observacaoNascimento;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="naturalidade">Naturalidade</label></th>
						<td>
							<?php print $this->dropdown_states( 'naturalidade', $dados_pessoais[ 'naturalidade' ], false, 'disabled="disabled"' ); ?>
						</td>
						
						<td >
							<input <?php if($avaliacaoNaturalidade == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoNaturalidade"> sim
							<input <?php if($avaliacaoNaturalidade == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoNaturalidade"> não
							<input <?php if($avaliacaoNaturalidade == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoNaturalidade"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoNaturalidade"><?php echo $observacaoNaturalidade;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for='email'>E-mail</label></th>
						<td>
							<input type="text" id="email" name="email" value="<?php print $user->user_email; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoEmail == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoEmail"> sim
							<input <?php if($avaliacaoEmail == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoEmail"> não
							<input <?php if($avaliacaoEmail == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoEmail"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoEmail"><?php echo $observacaoEmail;?></textarea>
						</td>
					</tr>
				</table>

				<h3>Dados para Localização</h3>
				<table class="form-table">
					<tr>
						<th width="100"><label for="endereco">Endereço</label></th>
						<td width="300">
							<input type="text" name="endereco" id="endereco" value="<?php print $dados_geograficos[ 'endereco' ]; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoEndereco == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoEndereco"> sim
							<input <?php if($avaliacaoEndereco == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoEndereco"> não
							<input <?php if($avaliacaoEndereco == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoEndereco"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoEndereco"><?php echo $observacaoEndereco;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="complemento">Complemento</label></th>
						<td>
							<input type="text" name="complemento" id="complemento" value="<?php print $dados_geograficos[ 'complemento' ]; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoComplemento == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoComplemento"> sim
							<input <?php if($avaliacaoComplemento == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoComplemento"> não
							<input <?php if($avaliacaoComplemento == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoComplemento"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoComplemento"><?php echo $observacaoComplemento;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="e-mail">Bairro</label></th>
						<td>
							<input type="text" name="bairro" id="bairro" value="<?php print $dados_geograficos[ 'bairro' ]; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoBairro == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoBairro"> sim
							<input <?php if($avaliacaoBairro == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoBairro"> não
							<input <?php if($avaliacaoBairro == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoBairro"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoBairro"><?php echo $observacaoBairro;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="cep">CEP</label></th>
						<td>
							<input type="text" name="cep" id="cep" value="<?php print $dados_geograficos[ 'cep' ]; ?>" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoCEP == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoCEP"> sim
							<input <?php if($avaliacaoCEP == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoCEP"> não
							<input <?php if($avaliacaoCEP == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoCEP"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoCEP"><?php echo $observacaoCEP;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="cidade">Cidade</label></th>
						<td>
							<input type="text" name="cidade" id="cidade" value="<?php print $dados_geograficos[ 'cidade' ]; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoCidade == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoCidade"> sim
							<input <?php if($avaliacaoCidade == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoCidade"> não
							<input <?php if($avaliacaoCidade == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoCidade"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoCidade"><?php echo $observacaoCidade;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="estado">Estado</label></th>
						<td>
							<?php print $this->dropdown_states( 'estado', $dados_geograficos[ 'estado' ], false, 'disabled="disabled"' ); ?>
						</td>
						<td >
							<input <?php if($avaliacaoEstado == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoEstado"> sim
							<input <?php if($avaliacaoEstado == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoEstado"> não
							<input <?php if($avaliacaoEstado == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoEstado"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoEstado"><?php echo $observacaoEstado;?></textarea>
						</td>
					</tr>
				</table>

				<h3>Dados Profissionais</h3>
				<table class="form-table">
					<tr>
						<th width="100"><label for="formacao">Formação</label></th>
						<td width="300">
							<input type="text" name="formacao" id="formacao" value="<?php print $dados_profissionais[ 'formacao' ]; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoFormacao == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoFormacao"> sim
							<input <?php if($avaliacaoFormacao == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoFormacao"> não
							<input <?php if($avaliacaoFormacao == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoFormacao"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoFormacao"><?php echo $observacaoFormacao;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="atuacao">Área de Atuação</label></th>
						<td>
							<input type="text" name="atuacao" id="atuacao" value="<?php print $dados_profissionais[ 'atuacao' ]; ?>" class="regular-text" disabled="disabled" /><br />
						
							<input type="text" name="formacao" id="formacao" value="<?php print $dados_profissionais[ 'formacao' ]; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoAtuacao == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoAtuacao"> sim
							<input <?php if($avaliacaoAtuacao == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoAtuacao"> não
							<input <?php if($avaliacaoAtuacao == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoAtuacao"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoAtuacao"><?php echo $observacaoAtuacao;?></textarea>
						</td>
					</tr>

					<tr>
						<th><label for="biografia">Apresentação</label></th>
						<td>
							<textarea style="width:300px" name="biografia" id="biografia" rows="5" cols="5" disabled="disabled"><?php print $dados_profissionais[ 'biografia' ]; ?></textarea><br />
						</td>
						<td >
							<input <?php if($avaliacaoApresentacao == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoApresentacao"> sim
							<input <?php if($avaliacaoApresentacao == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoApresentacao"> não
							<input <?php if($avaliacaoApresentacao == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoApresentacao"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoApresentacao"><?php echo $observacaoApresentacao;?></textarea>
						</td>
					</tr>
					<?php $segmento = get_user_meta( $user->ID, 'segmento', true ); ?>
					<tr>
						<th><label for="segmento">Segmento</label></th>
						<td>
							<input type="text" name="segmento" id="segmento" value="<?php print $segmento; ?>" class="regular-text" disabled="disabled" /><br />
						</td>
						<td >
							<input <?php if($avaliacaoSegmento == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoSegmento"> sim
							<input <?php if($avaliacaoSegmento == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoSegmento"> não
							<input <?php if($avaliacaoSegmento == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoSegmento"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoSegmento"><?php echo $observacaoSegmento;?></textarea>
						</td>
					</tr>
				</table>

				<h3>Anexos</h3>
				<table class="form-table">
					<?php $comprovante_atuacao = get_user_meta( $user->ID, 'comprovante_atuacao', true ); ?>
					<tr>
						<th width="100"><label for="comprovante_atuacao">Comprovante de Três Anos de Atuação no Setor</label></th>
						<td width="300">
							<?php if( empty( $comprovante_atuacao ) ) print 'nenhum'; ?>
							<a target="_blank" href="<?php print $comprovante_atuacao; ?>"><?php print $comprovante_atuacao; ?></a>
						</td>
						<td >
							<input <?php if($avaliacaoComprovanteAtuacaoSetor == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoComprovanteAtuacaoSetor"> sim
							<input <?php if($avaliacaoComprovanteAtuacaoSetor == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoComprovanteAtuacaoSetor"> não
							<input <?php if($avaliacaoComprovanteAtuacaoSetor == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoComprovanteAtuacaoSetor"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoComprovanteAtuacaoSetor"><?php echo $observacaoComprovanteAtuacaoSetor;?></textarea>
						</td>
					</tr>

					<?php $comprovante_identidade = get_user_meta( $user->ID, 'comprovante_identidade', true ); ?>
					<tr>
						<th><label for="comprovante_identidade">Identidade</label></th>
						<td>
							<?php if( empty( $comprovante_identidade ) ) print 'nenhum'; ?>
							<a target="_blank"  href="<?php print $comprovante_identidade; ?>"><?php print $comprovante_identidade; ?></a>
						</td>
						<td >
							<input <?php if($avaliacaoComprovanteIdentidade == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoComprovanteIdentidade"> sim
							<input <?php if($avaliacaoComprovanteIdentidade  == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoComprovanteIdentidade"> não
							<input <?php if($avaliacaoComprovanteIdentidade  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoComprovanteIdentidade"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoComprovanteIdentidade"><?php echo $observacaoComprovanteIdentidade;?></textarea>
						</td>
					</tr>

					<?php $comprovante_cpf = get_user_meta( $user->ID, 'comprovante_cpf', true ); ?>
					<tr>
						<th><label for="comprovante_cpf">CPF</label></th>
						<td>
							<?php if( empty( $comprovante_cpf ) ) print 'nenhum'; ?>
							<a target="_blank"  href="<?php print $comprovante_cpf; ?>"><?php print $comprovante_cpf; ?></a>
						</td>
						<td >
							<input <?php if($avaliacaoComprovanteCPF == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoComprovanteCPF"> sim
							<input <?php if($avaliacaoComprovanteCPF == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoComprovanteCPF"> não
							<input <?php if($avaliacaoComprovanteCPF  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoComprovanteCPF"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoComprovanteCPF"><?php echo $observacaoComprovanteCPF;?></textarea>
						</td>
					</tr>

					<?php $comprovante_residencia = get_user_meta( $user->ID, 'comprovante_residencia', true ); ?>
					<tr>
						<th><label for="comprovante_residencia">Comprovante de Residência</label></th>
						<td>
							<?php if( empty( $comprovante_residencia ) ) print 'nenhum'; ?>
							<a target="_blank"  href="<?php print $comprovante_residencia; ?>"><?php print $comprovante_residencia; ?></a>
						</td>
						<td >
							<input <?php if($avaliacaoComprovanteResidencia == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoComprovanteResidencia"> sim
							<input <?php if($avaliacaoComprovanteResidencia == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoComprovanteResidencia"> não
							<input <?php if($avaliacaoComprovanteResidencia  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoComprovanteResidencia"> não se aplica
						</td>
						<td>Observações<br>
							<textarea name="observacaoComprovanteResidencia"><?php echo $observacaoComprovanteResidencia;?></textarea>
						</td>
						
					</tr>
				</table>

				<h3>Declaração</h3>
				<table class="form-table">
					<tr>
						<th><strong>Declaro para os devidos fins que:</strong></th>
						<td>
							<label><input type='checkbox' name='declaracao_veracidade' value='declaracao_veracidade' <?php if( 'declaracao_veracidade' == get_user_meta( $user->ID, 'declaracao_veracidade', true ) ) print 'checked="checked"'; ?> disabled="disabled"> Os dados informados em meu cadastro estão de acordo com os <a href='#' title='Termos de Veracidade das Informações' target='_blank'>Termos de Veracidade das Informações</a>; *</label><br />
							<label><input type='checkbox' name='declaracao_pnc' value='declaracao_pnc' <?php if( 'declaracao_pnc' == get_user_meta( $user->ID, 'declaracao_pnc', true ) ) print 'checked="checked"'; ?> disabled="disabled"> Tenho conhecimento do <a href='http://pnc.culturadigital.br/' title='Plano Nacional de Cultura' target='_blank'>Plano Nacional de Cultura</a>; *</label><br />
							<label><input type='checkbox' name='declaracao_das' value='declaracao_das' <?php if( 'declaracao_das' == get_user_meta( $user->ID, 'declaracao_das', true ) ) print 'checked="checked"'; ?> disabled="disabled"> Não sou detentor de cargo comissionado na administração pública federal, estadual, distrital ou municipal.</label><br />
						</td>
					</tr>
				</table>

				<?php if( 'declaracao_das' !== get_user_meta( $user->ID, 'declaracao_das', true ) ) : ?>
					<h3>Dados Comissionado</h3>
					<?php $cargo = get_user_meta( $user->ID, 'cargo', true ); ?>
					<table class="form-table">
						<tr>
							<th width="100"><label for="cargo">Cargo</label></th>
							<td width="300">
								<input type="text" name="cargo" id="cargo" value="<?php print $cargo; ?>" class="regular-text" disabled="disabled" /><br />
							</td>
							<td>
								<input <?php if($avaliacaoCargo == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoCargo"> sim
								<input <?php if($avaliacaoCargo == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoCargo"> não
								<input <?php if($avaliacaoCargo  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoCargo"> não se aplica
							</td>
							<td>
								Observações<br>
								<textarea name="observacaoCargo"><?php echo $observacaoCargo;?></textarea>
							</td>
						</tr>

						<?php $comprovante_comissionado = get_user_meta( $user->ID, 'comprovante_comissionado', true ); ?>
						<tr>
							<th><label for="comprovante_comissionado">Comprovante de Comissionado</label></th>
							<td>
								<?php if( empty( $comprovante_comissionado ) ) print 'nenhum'; ?>
								<a href="<?php print $comprovante_comissionado; ?>"><?php print $comprovante_comissionado; ?></a>
							</td>
							<td>
								<input <?php if($avaliacaoComprovanteComissionado == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoComprovanteComissionado"> sim
								<input <?php if($avaliacaoComprovanteComissionado == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoComprovanteComissionado"> não
								<input <?php if($avaliacaoComprovanteComissionado  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoComprovanteComissionado"> não se aplica
							</td>
							<td>
								Observações<br>
								<textarea name="observacaoComprovanteComissionado"><?php echo $observacaoComprovanteComissionado;?></textarea>
							</td>
						</tr>					
						
					</table>
					<?php endif; ?>			
						
						
				
				<h3>Avaliar ELEITOR</h3>
				<div class="postbox">
					<div class="inside">
						<table class="form-table">
							<tr>
								<th><label for="avaliacao">Situação</label></th>
								<td>
									<label style="color:#009900;"><input type="radio" name="avaliacaoEleitor" value="valido" <?php if( 'valido' == $avaliacaoEleitor ) print 'checked="checked"'; ?> /> Validado</label><br />
									<label style="color:#990000;"><input type="radio" name="avaliacaoEleitor" value="invalido" <?php if( 'invalido' == $avaliacaoEleitor ) print 'checked="checked"'; ?> /> Não Validado</label><br />
								</td>
							</tr>

							<tr>
								<th><label for="observacoesEleitor">Observações</label></th>
								<td>
									<textarea id="observacaoEleitor" name="observacaoEleitor" cols="50" rows="5" tabindex="2" maxlength="3000" class="limit-chars"><?php print $observacaoEleitor; ?></textarea>
								</td>
							</tr>
						</table>
					</div>
				</div>				
				

				<?php $candidatura = get_user_meta( $user->ID, 'candidatura', true ); ?>
				<?php if( !empty( $candidatura ) ) : ?>
					<h3>Dados do Candidato</h3>
					<table class="form-table">
						<tr>
							<th width="100"><label for="propostas">Carta Programa</label></th>
							<td width="300">
								<textarea name="propostas" id="propostas" rows="5" cols="30" disabled="disabled"><?php print $dados_candidatura[ 'propostas' ]; ?></textarea><br />
							</td>
							<td>
								<input <?php if($avaliacaoPropostas == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoPropostas"> sim
								<input <?php if($avaliacaoPropostas == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoPropostas"> não
								<input <?php if($avaliacaoPropostas  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoPropostas"> não se aplica
							</td>
							<td>
								Observações<br>
								<textarea name="observacaoPropostas"><?php echo $observacaoPropostas;?></textarea>
							</td>
						</tr>
					</table>

					<h3>Anexos</h3>
					<table class="form-table">
						<tr>
							<th width="100"><label for="curriculo">Comprovação de Atuação Cultural</label></th>
							<td width="300">
								<?php if( empty( $dados_candidatura[ 'curriculo' ] ) ) print 'nenhum'; ?>
								<a href="<?php print $dados_candidatura[ 'curriculo' ]; ?>"><?php print $dados_candidatura[ 'curriculo' ]; ?></a>
							</td>
							<td>
								<input <?php if($avaliacaoComprovanteAtuacaoCultural == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoComprovanteAtuacaoCultural"> sim
								<input <?php if($avaliacaoComprovanteAtuacaoCultural == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoComprovanteAtuacaoCultural"> não
								<input <?php if($avaliacaoComprovanteAtuacaoCultural  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoComprovanteAtuacaoCultural"> não se aplica
							</td>
							<td>
								Observações<br>
								<textarea name="observacaoComprovanteAtuacaoCultural"><?php echo $observacaoComprovanteAtuacaoCultural;?></textarea>
							</td>
						</tr>

						<tr>
							<th><label for="portfolio">Portfólio</label></th>
							<td>
								<?php if( empty( $dados_candidatura[ 'portfolio' ] ) ) print 'nenhum'; ?>
								<a href="<?php print $dados_candidatura[ 'portfolio' ]; ?>"><?php print $dados_candidatura[ 'portfolio' ]; ?></a>
							</td>
							<td>
								<input <?php if($avaliacaoPortfolio == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoPortfolio"> sim
								<input <?php if($avaliacaoPortfolio == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoPortfolio"> não
								<input <?php if($avaliacaoPortfolio  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoPortfolio"> não se aplica
							</td>
							<td>
								Observações<br>
								<textarea name="observacaoComprovantePortfolio"><?php echo $observacaoComprovantePortfolio;?></textarea>
							</td>
						</tr>

						<tr>
							<th><label for="apoio">Carta de Apoio</label></th>
							<td>
								<?php if( empty( $dados_candidatura[ 'apoio' ] ) ) print 'nenhum'; ?>
								<a href="<?php print $dados_candidatura[ 'apoio' ]; ?>"><?php print $dados_candidatura[ 'apoio' ]; ?></a>
							</td>
							<td>
								<input <?php if($avaliacaoCartaApoio == 1) echo "checked = checked"; ?> value="1" type="radio" name="avaliacaoCartaApoio"> sim
								<input <?php if($avaliacaoCartaApoio == 2) echo "checked = checked"; ?> value="2" type="radio" name="avaliacaoCartaApoio"> não
								<input <?php if($avaliacaoCartaApoio  == 3) echo "checked = checked"; ?> value="3" type="radio" name="avaliacaoCartaApoio"> não se aplica
							</td>
							<td>
								Observações<br>
								<textarea name="observacaoCartaApoio"><?php echo $observacaoCartaApoio;?></textarea>
							</td>
						</tr>
					</table>
					
				
				<h3>Avaliar CANDIDATO</h3>
				<div class="postbox">
					<div class="inside">
						<table class="form-table">
							<tr>
								<th><label for="avaliacao">Situação</label></th>
								<td>
									<label style="color:#009900;"><input type="radio" name="avaliacaoCandidato" value="valido" <?php if( 'valido' == $avaliacaoCandidato ) print 'checked="checked"'; ?> /> Validado</label><br />
									<label style="color:#990000;"><input type="radio" name="avaliacaoCandidato" value="invalido" <?php if( 'invalido' == $avaliacaoCandidato ) print 'checked="checked"'; ?> /> Não Validado</label><br />
								</td>
							</tr>

							<tr>
								<th><label for="observacoes">Observações</label></th>
								<td>
									<textarea id="observacaoCandidato" name="observacaoCandidato" cols="50" rows="5" tabindex="2" maxlength="3000" class="limit-chars"><?php print $observacaoCandidato; ?></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="right">		

									<button type="submit" name="avaliar" class="button-primary" tabindex="1000" onclick="return confirm( 'Confira atentamente seus dados e arquivos anexados!' );">Salvar Avaliação</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
				

				<?php endif; ?>
				
				<table class='form-table'>
					<th><label for="observacoes">Observações Antigas</label></th>
					<td>
						
						<textarea disabled cols="50" rows="5" tabindex="2" maxlength="3000" class="limit-chars"><?php echo OBSERVACOES_ANTIGAS;?></textarea>
					</td>
				</table>	

				<br><br><br>

				<?php $this->formulario_avaliacao( $user, $candidatura, $dados_candidatura[ 'propostas' ] ); ?>
				<table class='form-table'>
					<tr>
						<td colspan="2" align="right">		

							<button type="submit" name="avaliar" class="button-primary" tabindex="1000" onclick="return confirm( 'Confira atentamente seus dados e arquivos anexados!' );">Salvar Avaliação</button>
						</td>
					</tr>
				</table>
			</div>
			</form>

		<?php
	}

	/**
	 * formulario de avaliação
	 *
	 * @name    formulario_avaliacao
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-05-15
	 * @updated 2012-05-15
	 * @return  void
	 */
	function formulario_avaliacao( $participante, $candidatura = false, $propostas = null )
	{
		if( !current_user_can( 'cnpc_habilitar' ) )
			return false;

		global $user_ID, $wpdb;

		$nonce = isset( $_POST[ 'nonce' ] ) ? $_POST[ 'nonce' ] : "";

		// verificar se o envio foi feito pelo formulário identificado
		if( wp_verify_nonce( $nonce, 'cnpc_avaliacao' ) )
		{
			$avaliador      = $user_ID;
			$avaliacao      = $_POST[ 'avaliacao' ];
			$observacoes    = $_POST[ 'observacoes' ];
			$data_avaliacao = date( 'Y-m-d H:i' );
			
			

			$setorial       = get_bloginfo( 'url' );

			update_user_meta( $participante->ID, 'avaliador', $avaliador );
			update_user_meta( $participante->ID, 'avaliacao', $avaliacao );
			update_user_meta( $participante->ID, 'observacoes', $observacoes );
			update_user_meta( $participante->ID, 'data_avaliacao', $data_avaliacao );			
			
			
			
			$wp_object_participante = new WP_User( $participante->ID );

			$dados_pessoais = $this->get_dados_pessoais( $participante->user_login);
			$nicename       = sanitize_title( $dados_pessoais[ 'nome' ] );

			// update user_nicename
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->users} SET user_nicename = %s WHERE ID = %d LIMIT 1", $nicename, $user_ID ) );
			
			
			if( 'habilitado' == $avaliacao )
			{
				if( $candidatura )
				{
					$wp_object_participante->set_role( 'candidato' );

					$mensagem  = "<p>Prezado {$participante->display_name}</p>";

					$mensagem .= "<p>Parabéns! Sua candidatura para o <a href='http://www.cultura.gov.br/setoriais/' title='Fórum Nacional Setorial' target='_blank'>Fórum Nacional Setorial foi aprovado!</p>";
					$mensagem .= "<p>Conheça os candidatos, participe das discussões e ajude a eleger o representante de sua setorial!</p>";

					$post[ 'post_name' ]    = $participante->user_nicename;
					$post[ 'post_title' ]   = "Carta-Programa: {$participante->display_name}";
					$post[ 'post_content' ] = $propostas;
					$post[ 'post_status' ]  = 'publish';
					$post[ 'post_author' ]  = $participante->ID;

					wp_insert_post( $post );
				}
				else
				{
					$wp_object_participante->set_role( 'eleitor' );

					$mensagem  = "<p>Prezado {$participante->display_name}</p>";

					$mensagem .= "<p>Parabéns! Sua cadastro para o <a href='{$setorial}' title='Fórum Nacional Setorial' target='_blank'>Fórum Nacional Setorial</a> foi aprovado!</p>";
					$mensagem .= "<p>Conheça os candidatos, participe das discussões e ajude a eleger o representante de sua setorial!</p>";
					$mensagem .= "<p><strong>Ministério da Cultura</strong></p>";
				}
			}
			else
			{
				$wp_object_participante->set_role( 'participante' );

				$mensagem  = "<p>Prezado {$participante->display_name}</p>";

				$mensagem .= "<p>Seu cadastro para o <a href='{$setorial}' title='Fórum Nacional Setorial' target='_blank'>Fórum Nacional Setorial</a> não foi aprovado pelo(s) seguinte(s) motivo(s):</p>";
				$mensagem .= "<p>{$observacoes}</p>";
				$mensagem .= "<p>Você tem cinco dias para se adequar. Para isso basta fazer o <a href='{$setorial}' title='Fórum Nacional Setorial' target='_blank'>login</a> e acessar a página de <a href='{$setorial}/cadastro/'>cadastro</a>.</p>";
				$mensagem .= "<p><strong>Ministério da Cultura</strong></p>";
			}
			
			
			if( !wp_mail( $participante->user_email, 'CNPC: Cadastro', $mensagem, 'content-type: text/html' ) )
				print "<div class='error'><p>Falha ao enviar e-mail!</p></div>";

			print "<div class='updated'><p>Avaliação Atualizado!</p></div>";
			
		}

		$avaliador      	 = get_user_meta( $participante->ID, 'avaliador', true );
		$avaliacaoInscrito 	 = get_user_meta( $participante->ID, 'avaliacao', true );
		$observacoesInscrito = get_user_meta( $participante->ID, 'observacoes', true );
		$data_avaliacao      = get_user_meta( $participante->ID, 'data_avaliacao', true );
		

		$analista       = get_userdata( $avaliador );

		$nonce  = wp_create_nonce( 'cnpc_avaliacao' );

		?>
			<form id="avaliacao" method="post">
				<input type='hidden' name='nonce' value='<?php print $nonce; ?>' />				
								
				<h3>Habilitação do inscrito.</h3>
				<div class="postbox">
					<div class="inside">
						<table class="form-table">
							<tr>
								<th><label for="avaliacao">Presidente da Comissão</label></th>
								<td>
									<label style="color:#009900;"><input type="radio" name="avaliacaoInscrito" value="habilitado" <?php if( 'habilitado' == $avaliacaoInscrito ) print 'checked="checked"'; ?> /> Habilitado</label><br />
									<label style="color:#990000;"><input type="radio" name="avaliacaoInscrito" value="inabilitado" <?php if( 'inabilitado' == $avaliacaoInscrito ) print 'checked="checked"'; ?> /> Inabilitado</label><br />
								</td>
							</tr>

							<tr>
								<th><label for="observacoes">Observações *</label></th>
								<td>
									<textarea id="observacaoInscrito" name="observacaoInscrito" cols="50" rows="5" tabindex="2" maxlength="3000" class="limit-chars"><?php print $observacoesInscrito; ?></textarea>
								</td>
							</tr>	
							<tr>
								<td colspan=2 align="right">
									<?php if( !empty( $data_avaliacao ) and !empty( $avaliador ) ) : ?>
										<small>analisado em <?php print date( 'd\/m\/Y H:i', strtotime( $data_avaliacao ) + ( get_option( 'gmt_offset' ) * 3600 ) ); ?> por <strong><?php print $analista->display_name; ?></small>
									<?php endif; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</form>
		<?php
	}

	/**
	 * verificar se esse usuário já votou
	 *
	 * @name    user_voted
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-05-22
	 * @updated 2012-05-22
	 * @return  mixed
	 */
	function user_voted( $user_id )
	{
		global $wpdb;

		if( empty( $user_id ) )
			return false;

		$candidato_id = $wpdb->get_var( $wpdb->prepare( "SELECT id_candidato FROM {$wpdb->cnpc_eleicao} WHERE id_eleitor = %d", $user_id ) );

		if( empty( $candidato_id ) )
			return false;

		return $candidato_id;
	}

	/**
	 * botão de votação
	 *
	 * @name    vote_button
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-05-22
	 * @updated 2012-07-06
	 * @return  void
	 */
	function vote_button( $candidato_id )
	{
		global $user_ID;

		
		$voted_candidato_id = $this->user_voted( $user_ID );

		if( !empty( $voted_candidato_id ) )
		{
			$candidato = get_userdata( $voted_candidato_id );

			print "<span class='voted' title='você votou no {$candidato->display_name}'>você já votou</span>";

			return false;
		}

		if( !$this->votacoes_abertas() )
			return false;

		if( !user_can( $candidato_id, 'candidato' ) )
			return false;

		$url   = get_bloginfo( 'url' );
		$nonce = wp_create_nonce( 'cnpc_vote' );

		print "<a href='{$url}/?candidato_id={$candidato_id}&nonce={$nonce}' title='votar' class='vote' onclick='return confirm( 'Tem certeza que deseja votar nesse candidato? Após concluir seu voto você não poderá mais votar!' );'>Votar</a>";
	}

	/**
	 * vote
	 *
	 * @name    vote
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-05-22
	 * @updated 2012-05-22
	 * @return  void
	 */
	function vote()
	{
		global $wpdb, $user_ID;

		if( !current_user_can( 'cnpc_vote' ) )
			return false;

		$nonce = null;
		
		if( isset( $_GET[ 'candidato_id' ] ) and isset( $_GET[ 'nonce' ] ) )
		{
			$nonce        = isset( $_GET[ 'nonce' ] ) ? $_GET[ 'nonce' ] : "";
			$candidato_id = ( int ) $_GET[ 'candidato_id' ];
		}

		if( !wp_verify_nonce( $nonce, 'cnpc_vote' ) )
			return false;

		if( $this->user_voted( $user_ID ) )
			return false;

		if( !user_can( $candidato_id, 'candidato' ) )
			return false;

		$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->cnpc_eleicao} ( id_eleitor, id_candidato, registrado ) VALUES ( %d, %d, %s )", $user_ID, $candidato_id, date( 'Y-m-d H:i' ) ) );

		wp_redirect( get_author_posts_url( $candidato_id ) . '?sussa=1' ); exit();
	}

	/**
	 * setup dashboard
	 *
	 * @name    dashboard_setup
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-06-14
	 * @updated 2012-06-14
	 * @return  void
	 */
	function dashboard_setup() {
		wp_add_dashboard_widget( 'dashboard_cnpc' , 'Eleições Setoriais' , array( &$this, 'dashboard' ) );
	}

	/**
	 * dashboard
	 *
	 * @name    dashboard
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-06-14
	 * @updated 2012-07-05
	 * @return  void
	 */
	function dashboard()
	{
		global $wpdb;

		if( current_user_can( 'administrator' ) )
		{	
			$check_nonce = isset( $_POST[ 'nonce' ] ) ? $_POST[ 'nonce' ] : ""; 

			// salvar os prazos de inscrição e votação
			if( wp_verify_nonce( $check_nonce, 'cnpc_dashboard' ) )
			{
				if( preg_match( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $_POST[ 'ini_inscricao' ] ) )
				{
					// transformar data no padrão universal
					$ini_inscricao = preg_replace( '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', '$3-$2-$1', $_POST[ 'ini_inscricao' ] );

					// transformar data em timestamp
					$ini_inscricao = strtotime( $ini_inscricao );

					// salvar timestamp
					update_option( 'cnpc_ini_inscricao', $ini_inscricao );
				}

				if( preg_match( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $_POST[ 'fim_inscricao' ] ) )
				{
					$fim_inscricao = preg_replace( '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', '$3-$2-$1', $_POST[ 'fim_inscricao' ] );

					// transformar data em timestamp
					$fim_inscricao = strtotime( $fim_inscricao );

					// salvar timestamp
					update_option( 'cnpc_fim_inscricao', $fim_inscricao );
				}

				if( preg_match( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $_POST[ 'ini_votacao' ] ) )
				{
					$ini_votacao = preg_replace( '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', '$3-$2-$1', $_POST[ 'ini_votacao' ] );

					// transformar data em timestamp
					$ini_votacao = strtotime( $ini_votacao );

					// salvar timestamp
					update_option( 'cnpc_ini_votacao', $ini_votacao );
				}

				if( preg_match( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $_POST[ 'fim_votacao' ] ) )
				{
					$fim_votacao = preg_replace( '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', '$3-$2-$1', $_POST[ 'fim_votacao' ] );

					// transformar data em timestamp
					$fim_votacao = strtotime( $fim_votacao );

					// salvar timestamp
					update_option( 'cnpc_fim_votacao', $fim_votacao );
				}
			}
		}

		// data de hoje
		$today         = mktime( 0, 0, 0, date( 'm' ), date( 'd' ), date( 'Y' ) );

		// data de inscrição
		$ini_inscricao = get_option( 'cnpc_ini_inscricao', true );
		$fim_inscricao = get_option( 'cnpc_fim_inscricao', true );

		// data de votação
		$ini_votacao   = get_option( 'cnpc_ini_votacao', true );
		$fim_votacao   = get_option( 'cnpc_fim_votacao', true );

		$nonce         = wp_create_nonce( 'cnpc_dashboard' );

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

		$readonly = null;

		// quantidade de inscritos
		$eleitores  = get_users( 'meta_key=candidatura&meta_value=candidatura&meta_compare=!=' );
		$candidatos = get_users( 'meta_key=candidatura&meta_value=candidatura' );

		//$participantes = get_users( 'meta_key=candidatura' );

		// quantidade de inscritos por estado
		$meta       = $wpdb->get_blog_prefix() . 'capabilities';
		//$inscritos  = $wpdb->get_col( $wpdb->prepare( "SELECT g.estado AS estado FROM {$wpdb->users} AS u LEFT JOIN {$wpdb->usermeta} AS um ON ( u.ID = um.user_id ) LEFT JOIN {$wpdb->cnpc_dados_geograficos} AS g ON ( u.user_login = g.login ) WHERE um.meta_key = %s", $meta ) );
		$inscritos  = $wpdb->get_results( $wpdb->prepare( "SELECT g.estado As estado, dois.meta_value as candidatura FROM {$wpdb->users} AS u LEFT JOIN {$wpdb->usermeta} AS um ON ( u.ID = um.user_id ) LEFT JOIN {$wpdb->usermeta} AS dois ON ( u.ID = dois.user_id ) LEFT JOIN {$wpdb->cnpc_dados_geograficos} AS g ON ( u.user_login = g.login ) WHERE um.meta_key = %s AND dois.meta_key = 'candidatura' ", $meta ) );

		$quantidade_eleitores = array();
		$quantidade_candidatos = array();
		$quantidade_por_estado = array();
		$quantidade_por_regiao = array();

		foreach( $inscritos as $inscricao )
		{
			$uf = $inscricao->estado;

			$quantidade_eleitores[ $uf ]  = !empty( $quantidade_eleitores[ $uf ] ) ? $quantidade_eleitores[ $uf ] : 0;
			$quantidade_candidatos[ $uf ] = !empty( $quantidade_candidatos[ $uf ] ) ? $quantidade_candidatos[ $uf ] : 0;
			$quantidade_por_estado[ $uf ] = !empty( $quantidade_por_estado[ $uf ] ) ? $quantidade_por_estado[ $uf ] : 0;

			if( empty( $inscricao->candidatura ) )
				$quantidade_eleitores[ $uf ] = $quantidade_eleitores[ $uf ] + 1;
			else
				$quantidade_candidatos[ $uf ] = $quantidade_candidatos[ $uf ] + 1;

			$quantidade_por_estado[ $uf ] = $quantidade_por_estado[ $uf ] + 1;
		}

		?>
			<div style="float: left; width: 45%; padding: 1%;">
				<h4 style="color: #8F8F8F; font: normal 14px sans-serif; border-bottom: 1px solid #ECECEC; padding: 10px 0px;">Inscrições</h4>
				<table style="color: #21759B; font: normal 12px sans-serif;">
					<tr>
						<th style="font: normal 18px georgia; line-height: 16px;"><?php print count( $eleitores ); ?></th>
						<td>Eleitores</td>
					</tr>
					<tr>
						<th style="font: normal 18px georgia; line-height: 16px;"><?php print count( $candidatos ); ?></th>
						<td>Candidatos</td>
					</tr>
					<tr>
						<th style="font: normal 18px georgia; line-height: 16px;"><?php print count( $eleitores ) + count( $candidatos ); ?></th>
						<td>Total</td>
					</tr>
				</table>

				<?php if( !current_user_can( 'administrator' ) ) $readonly = 'disabled="disabled"'; ?>
				<form action="" method="post">
					<input type="hidden" name="nonce" value="<?php print $nonce; ?>" />

					<h4 style="color: #8F8F8F; font: normal 14px sans-serif; border-bottom: 1px solid #ECECEC; padding: 10px 0px;">Prazo das Inscrições</h4>
					<input type="text" name="ini_inscricao" size="10" maxlength="10" value="<?php print date( 'd/m/Y', $ini_inscricao ); ?>" <?php print $readonly; ?> /> a
					<input type="text" name="fim_inscricao" size="10" maxlength="10" value="<?php print date( 'd/m/Y', $fim_inscricao ); ?>" <?php print $readonly; ?> />

					<h4 style="color: #8F8F8F; font: normal 14px sans-serif; border-bottom: 1px solid #ECECEC; padding: 10px 0px;">Prazo das Votações</h4>
					<input type="text" name="ini_votacao" size="10" maxlength="10" value="<?php print date( 'd/m/Y', $ini_votacao ); ?>" <?php print $readonly; ?> /> a
					<input type="text" name="fim_votacao" size="10" maxlength="10" value="<?php print date( 'd/m/Y', $fim_votacao ); ?>" <?php print $readonly; ?> />

					<br />
					<?php if( current_user_can( 'administrator' ) ) : ?>
						<button class="button-primary">Salvar</button>
					<?php endif; ?>

					<small><?php print date( 'd/m/Y H:i', time() + ( get_option( 'gmt_offset' ) * 3600 ) ); ?></small>
				</form>
			</div>

			<div style="float: right; width: 45%; padding: 1%;">
				<h4 style="color: #8F8F8F; font: normal 14px sans-serif; border-bottom: 1px solid #ECECEC; padding: 10px 0px;">Inscrições por Estado</h4>
				<table style="color: #21759B; font: normal 12px sans-serif;">
					<?php foreach( $states as $key => $state ) : ?>
						<tr>
							<th style="font: normal 18px georgia; line-height: 16px;"><?php print isset( $quantidade_por_estado[ $key ] ) ? $quantidade_por_estado[ $key ] : 0; ?></th>
							<td><?php print $state; ?></td>
							<?php if( ( 'administrator' ) ) : ?>
								<td style="font: normal 12px georgia; padding-left: 10px;"><?php print isset( $quantidade_eleitores[ $key ] ) ? $quantidade_eleitores[ $key ] : 0; ?>E</td>
								<td style="font: normal 12px georgia; padding-left: 5px;"><?php print isset( $quantidade_candidatos[ $key ] ) ? $quantidade_candidatos[ $key ] : 0; ?>C</td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
			<br class="clear" />
		<?php
	}

	/**
	 * verificar se a temporada de voto está aberta
	 *
	 * @name    inscricoes_abertas
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-07-06
	 * @updated 2012-07-06
	 * @return  void
	 */
	function inscricoes_abertas()
	{
		/**********************************
		REMOVER
		**********************************/
		// return false;	
	
		// data de hoje
		$today         = time() + ( get_option( 'gmt_offset' ) * 3600 );

		// data de inscrição
		$ini_inscricao = get_option( 'cnpc_ini_inscricao', true );
		$fim_inscricao = get_option( 'cnpc_fim_inscricao', true );

		
		if( $today >= $ini_inscricao and $today <= $fim_inscricao )
			return true;

		return false;
	
	}

	/**
	 * verificar se a temporada de voto está aberta
	 *
	 * @name    votacoes_abertas
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-05-22
	 * @updated 2012-05-22
	 * @return  bool
	 */
	function votacoes_abertas()
	{
		// data de hoje
		$today       = time() + ( get_option( 'gmt_offset' ) * 3600 );

		// data de inscrição
		$ini_votacao = get_option( 'cnpc_ini_votacao', true );
		$fim_votacao = get_option( 'cnpc_fim_votacao', true );

		if( $today >= $ini_votacao and $today <= $fim_votacao )
			return true;

		return false;
	}

	/**
	 * editar o nicename
	 *
	 * @name    edit_nicename
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-06-26
	 * @updated 2012-06-26
	 * @return  void
	 */
	function edit_nicename( $user )
	{
		global $wpdb;

		$nicename = sanitize_title( $user->display_name );

		// update user_nicename
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->users} SET user_nicename = %s WHERE ID = %d LIMIT 1", $nicename, $user->id ) );
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    CNPC
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-06-14
	 * @return  void
	 */
	function CNPC()
	{
		// define plugin url
		$this->url = WP_PLUGIN_URL . '/' . $this->slug;

		// define plugin dir
		$this->dir = WP_PLUGIN_DIR . '/' . $this->slug;

		// define plugin tables
		$this->tables();
		$this->install_tables();

		// load languages
		load_plugin_textdomain( $this->slug, '', 'lang' );

		// install o plugin
		register_activation_hook( __FILE__, array( &$this, 'install' ) );

		// uninstall plugin
		register_deactivation_hook( __FILE__, array( &$this, 'uninstall' ) );

		// menu
		add_action( 'admin_menu', array( &$this, 'menus' ) );

		// hooks
		// carregar estilos e scripts no tema
		add_action( 'wp_head', array( &$this, 'frontend_styles' ) );
		add_action( 'wp_head', array( &$this, 'frontend_scripts' ) );

		// mostrar opção na lista de ações dos usuários
		add_action( 'user_row_actions', array( &$this, 'user_row_actions' ), 10, 2 );

		// mostrar estado na lista dos usuários
		add_filter( 'manage_users_columns', array( &$this, 'user_state_column' ) );
		add_action( 'manage_users_custom_column', array( &$this, 'user_state_column_content' ), 10, 3 );

		// votar
		add_action( 'init', array( &$this, 'vote' ) );

		// dashboard
		add_action( 'wp_dashboard_setup', array( &$this,'dashboard_setup' ) );

		// mostrar campos no perfil
		//add_action( 'show_user_profile', array( &$this, 'show_user_inscription' ) );
		add_action( 'edit_user_profile', array( &$this, 'edit_nicename' ) );

		// includes
		require( "{$this->dir}/inc/validator.php" );

		// extensions
		require( "{$this->dir}/cnpc-cadastro.php" );

		// widgets
		// require( "{$this->dir}/cnpc-widget.php" );

		// inicializar opções
		// update_option( 'data_votacao', '2012-07-30' );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

$CNPC = new CNPC();

?>