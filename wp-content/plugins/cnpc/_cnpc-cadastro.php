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
 */


class CNPC_Cadastro
{
	// ATRIBUTES /////////////////////////////////////////////////////////////////////////////////////

	// METHODS ///////////////////////////////////////////////////////////////////////////////////////
	/**
	 * shortcode: pessoa fisica
	 *
	 * @name    shortcode_pessoa_fisica
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-06-19
	 * @return  string
	 */
	function shortcode_pessoa_fisica( $args )
	{
		global $CNPC, $user_ID, $user_login, $user_email;

		// extrai os parametros do shortcode
		extract(
			shortcode_atts(
				array(
					'target'   => get_permalink(),
					'redirect' => get_permalink(),
				),
				$args
			)
		);

		// verificar se as inscrições estão abertas
		if( !$CNPC->inscricoes_abertas() )
		{
			$output .= '<div class="error">';
			$output .= '<p>Acesse o link para Cadastro <a href="http://cnpc.cultura.gov.br">http://cnpc.cultura.gov.br</a></p>';
			$output .= '</div>';

			return $output;
		}

		// if user logged, use database data
		if( !empty( $user_ID ) )
		{
			if( !current_user_can( 'cnpc' ) )
			{
				$output .= '<div class="error">';
				$output .= '<p>Só é permitido o cadastro em uma setorial.</p>';
				$output .= '</div>';

				return $output;
			}
			else
			{
				// in case the user not be a pessoa fisica
				if( !$CNPC->is_pessoa_fisica() )
				{
					$output .= '<div class="error">';
					$output .= '<p>Esse formulário é destinado apenas a pessoas físicas.</p>';
					$output .= '</div>';

					return $output;
				}

				// get user data
				$login = $user_login;
				$email = $user_email;

				// load data
				$dados_pessoais      = $CNPC->get_dados_pessoais( $user_login );
				$dados_geograficos   = $CNPC->get_dados_geograficos( $user_login );
				$dados_profissionais = $CNPC->get_dados_profissionais( $user_login );
				$dados_candidatura   = $CNPC->get_dados_candidatura( $user_login );

				$cargo               = get_user_meta( $user_ID, 'cargo', true);
				$segmento            = get_user_meta( $user_ID, 'segmento', true);

				// validate declaração
				if( 'declaracao_veracidade' == get_user_meta( $user_ID, 'declaracao_veracidade', true ) )
					$declaracao_veracidade = 'checked="checked"';

				if( 'declaracao_acompanhamento' == get_user_meta( $user_ID, 'declaracao_acompanhamento', true ) )
					$declaracao_acompanhamento = 'checked="checked"';

				if( 'declaracao_pnc' == get_user_meta( $user_ID, 'declaracao_pnc', true ) )
					$declaracao_pnc        = 'checked="checked"';

				if( 'declaracao_das' == get_user_meta( $user_ID, 'declaracao_das', true ) )
					$declaracao_das        = 'checked="checked"';

				// validate candidatura
				if( 'candidatura' == get_user_meta( $user_ID, 'candidatura', true ) )
				{
					// validate declaração
					if( 'declaracao_candidato_veracidade' == get_user_meta( $user_ID, 'declaracao_candidato_veracidade', true ) )
						$declaracao_candidato_veracidade = 'checked="checked"';

					$candidatura = 'checked="checked"';
				}

				$disabled = "disabled='disabled'";
			}
		}

		// if form was sended, use $_POST data
		if( wp_verify_nonce( $_POST[ 'nonce' ], 'cnpc_pessoa_fisica' ) )
		{
			// validate dados acesso
			if( isset( $_POST[ 'cpf' ] ) )   $login = $_POST[ 'cpf' ];
			if( isset( $_POST[ 'email' ] ) ) $email = $_POST[ 'email' ];

			$nascimento = array( preg_replace( '/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$2-$1', $_POST[ 'nascimento' ] ) );

			// validate dados pessoais
			if( isset( $_POST[ 'rg' ] ) )            $dados_pessoais[ 'rg' ]             = $_POST[ 'rg' ];
			if( isset( $_POST[ 'nome' ] ) )          $dados_pessoais[ 'nome' ]           = $_POST[ 'nome' ];
			if( isset( $_POST[ 'apelido' ] ) )       $dados_pessoais[ 'apelido' ]        = $_POST[ 'apelido' ];
			if( isset( $_POST[ 'nascimento' ] ) )    $dados_pessoais[ 'nascimento' ]     = $nascimento[ 0 ];
			if( isset( $_POST[ 'naturalidade' ] ) )  $dados_pessoais[ 'naturalidade' ]   = $_POST[ 'naturalidade' ];
			if( isset( $_POST[ 'nacionalidade' ] ) ) $dados_pessoais[ 'nacionalidade' ]  = $_POST[ 'nacionalidade' ];
			if( isset( $_POST[ 'etnia' ] ) )         $dados_pessoais[ 'etnia' ]          = $_POST[ 'etnia' ];

			// validate dados geográficos
			if( isset( $_POST[ 'endereco' ] ) )      $dados_geograficos[ 'endereco' ]    = $_POST[ 'endereco' ];
			if( isset( $_POST[ 'complemento' ] ) )   $dados_geograficos[ 'complemento' ] = $_POST[ 'complemento' ];
			if( isset( $_POST[ 'bairro' ] ) )        $dados_geograficos[ 'bairro' ]      = $_POST[ 'bairro' ];
			if( isset( $_POST[ 'cep' ] ) )           $dados_geograficos[ 'cep' ]         = $_POST[ 'cep' ];
			if( isset( $_POST[ 'cidade' ] ) )        $dados_geograficos[ 'cidade' ]      = $_POST[ 'cidade' ];
			if( isset( $_POST[ 'estado' ] ) )        $dados_geograficos[ 'estado' ]      = $_POST[ 'estado' ];

			// validate dados profissionais
			if( isset( $_POST[ 'formacao' ] ) )      $dados_profissionais[ 'formacao' ]  = $_POST[ 'formacao' ];
			if( isset( $_POST[ 'atuacao' ] ) )       $dados_profissionais[ 'atuacao' ]   = $_POST[ 'atuacao' ];
			if( isset( $_POST[ 'biografia' ] ) )     $dados_profissionais[ 'biografia' ] = $CNPC->clear_text( $_POST[ 'biografia' ] );

			// validate cargo
			if( isset( $_POST[ 'cargo' ] ) )         $dados_profissionais[ 'cargo' ] = $_POST[ 'cargo' ];
			if( isset( $_POST[ 'segmento' ] ) )      $segmento = $_POST[ 'segmento' ];

			// verificar se esse usuário é candidato
			if( 'candidatura' == $_POST[ 'candidatura' ] )
				$candidatura = 'checked="checked"';

			// validate dados candidatura
			if( isset( $_POST[ 'propostas' ] ) )   $dados_candidatura[ 'propostas' ]    = $CNPC->clear_text( $_POST[ 'propostas' ] );
		}

		$nonce  = wp_create_nonce( 'cnpc_pessoa_fisica' );

		if( current_user_can( 'cnpc' ) )
			$action = 'cnpc_update_pessoa_fisica';
		else
			$action = 'cnpc_insert_pessoa_fisica';

		// formulario
		$output = '<div id="cnpc" class="formulario_pessoa_fisica">';

		if( isset( $_GET[ 'sussa' ] ) )
		{
			// data updated
			$output .= '<div class="update">';

			if( 1 == $_GET[ 'sussa' ] )
			{
				$output .= '<p>Seus dados foram salvos! Por favor, confira os campos e continue seu cadastro.</p>';
			}

			if( 2 == $_GET[ 'sussa' ] )
			{
				$output .= '<p>Seus dados foram salvos! Por favor, confira se os campos estão corretamente preenchidos.</p>';
				$output .= '<p><strong>Sua inscrição só será válida após análise da comissão.</strong></p>';
			}

			$output .= '</div>';
		}

		// show errors
		$output .= $CNPC->show_error();

		$output .= "<form class='cadastro' action='{$target}' method='post' enctype='multipart/form-data'>";

		$output .= "
				<input type='hidden' name='action' value='{$action}' />
				<input type='hidden' name='nonce' value='{$nonce}' />
				<input type='hidden' name='redirect' value='{$redirect}' />
		";

		$output .= "<table>";

		$output .= "
			<tr>
				<td><small>* preenchimento obrigatório</small></td>
			</tr>
		";

		$output .= "
				<tr>
					<th colspan='2' class='post-title'>Cadastro de Eleitor</th>
				</tr>
		";

		$output .= "
				<tr>
					<th colspan='2'>Dados Pessoais</th>
				</tr>

				<tr>
					<td colspan='2'>
						<label for='nome'>Nome Completo: *</label><br>
						<input type='text' id='nome' name='nome' value='{$dados_pessoais[ 'nome' ]}' size='70' maxlength='100' />
					</td>
				</tr>

				<tr>
					<td colspan='2'>
						<label for='apelido'>Nome Artístico / Apelido:</label><br>
						<input type='text' id='apelido' name='apelido' value='{$dados_pessoais[ 'apelido' ]}' size='70' maxlength='100' />
					</td>
				</tr>

				<tr>
					<td>
						<label for='cpf'>CPF: *</label><br>
						<input type='text' id='cpf' name='cpf' value='{$login}' size='15' maxlength='11' {$disabled} /> <small>apenas números</small>
					</td>
					<td>
						<label for='rg'>RG: *</label><br>
						<input type='text' id='rg' name='rg' value='{$dados_pessoais[ 'rg' ]}' size='15' maxlength='15' /> <small>apenas números</small>
					</td>
				</tr>

				<tr>
					<td>
						<label for='nascimento'>Nascimento: *</label><br>
						<input type='text' id='nascimento' name='nascimento' value='" . date( 'd\/m\/Y', strtotime( $dados_pessoais[ 'nascimento' ] ) ) . "' size='10' maxlength='10' /> <small>dd/mm/yyyy</small>
					</td>
					<td>
						<label for='naturalidade'>Naturalidade: *</label><br>
						" . $CNPC->dropdown_states( 'naturalidade', $dados_pessoais[ 'naturalidade' ], false ) . "
					</td>
				</tr>

				<tr>
					<td colspan='2'>
						<label for='email'>E-mail: *</label><br>
						<input type='text' id='email' name='email' value='{$email}' size='70' maxlength='100' {$disabled} />
					</td>
				</tr>
		";

		// mostrar apenas no blog de cultura afro
		//if( 13 == get_current_blog_id() ) :
		if( 'Fórum Nacional Setorial  Culturas Afro-Brasileiras' == get_bloginfo( 'title' ) ) :
		$output .= "
				<tr>
					<td colspan='2'>
						<label for='etnia'>Comunidade:</label><br>
						<input type='text' id='etnia' name='etnia' value='{$dados_pessoais[ 'etnia' ]}' size='30' maxlength='100' />
					</td>
				</tr>
		";
		endif;

		// mostrar apenas no blog de cultura indígena
		//if( 14 == get_current_blog_id() ) :
		if( 'Fórum Nacional Setorial  Culturas dos Povos Indígenas' == get_bloginfo( 'title' ) ) :
		$output .= "
				<tr>
					<td colspan='2'>
						<label for='etnia'>Etnia:</label><br>
						<input type='text' id='etnia' name='etnia' value='{$dados_pessoais[ 'etnia' ]}' size='30' maxlength='100' />
					</td>
				</tr>
		";
		endif;

		if( !current_user_can( 'cnpc' ) )
		{
			$output .= "
				<tr>
					<th colspan='2'>Segurança</th>
				</tr>

				<tr>
					<td colspan='2'>
						<label for='senha'>Escolha sua senha: *</label><br>
						<input type='password' id='senha' name='senha' maxlength='100' />
					</td>
				</tr>
			";
		}

		$output .= "
				<tr>
					<td colspan='2'>
						<button type='submit'>Salvar Dados</button>
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<th colspan='2'>Localização</th>
				</tr>

				<tr>
					<td colspan='2'>
						<label for='endereco'>Endereço: *</label><br>
						<input type='text' id='endereco' name='endereco' value='{$dados_geograficos[ 'endereco' ]}' size='70' maxlength='100' />
					</div>
				</tr>

				<tr>
					<td colspan='2'>
						<label for='complemento'>Complemento:</label><br>
						<input type='text' id='complemento' name='complemento' value='{$dados_geograficos[ 'complemento' ]}' size='70' maxlength='100' />
					</div>
				</tr>

				<tr>
					<td>
						<label for='bairro'>Bairro: *</label><br>
						<input type='text' id='bairro' name='bairro' value='{$dados_geograficos[ 'bairro' ]}' size='30' maxlength='100' />
					</td>
					<td>
						<label for='cep'>CEP: *</label><br>
						<input type='text' id='cep' name='cep' value='{$dados_geograficos[ 'cep' ]}' size='10' maxlength='8' />
					</td>
				</tr>

				<tr>
					<td>
						<label for='cidade'>Cidade: *</label><br>
						<input type='text' id='cidade' name='cidade' value='{$dados_geograficos[ 'cidade' ]}' size='30' maxlength='100' />
					</td>
					<td>
						<label for='estado'>UF: *</label><br>
						" . $CNPC->dropdown_states( 'estado', $dados_geograficos[ 'estado' ], false ) . "
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<td colspan='2'>
						<button type='submit'>Salvar Dados</button>
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<th colspan='2'>Dados Profissionais</th>
				</tr>

				<tr>
					<td valign='top'>
						<label for='formacao'>Formação: *</label><br>
						<input type='text' id='formacao' name='formacao' value='{$dados_profissionais[ 'formacao' ]}' size='30' maxlength='100' />
					</td>
					<td valign='top'>
						<label for='atuacao'>Área de Atuação: *</label><br>
						<input type='text' id='atuacao' name='atuacao' value='{$dados_profissionais[ 'atuacao' ]}' size='30' maxlength='100' /><br>
						<small>Descrição do vínculo empregatício ou atuação profissional autônoma.</small>
					</td>
				</tr>

				<tr>
					<td colspan='2'>
						<label for='biografia'>Apresentação:</label><br>
						<textarea id='biografia' name='biografia' cols='100' rows='10' maxlength='3000' class='limit-chars'>{$dados_profissionais[ 'biografia' ]}</textarea>
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<td colspan='2'>
						<label for='segmento'>Segmento da área técnico-artística ou de patrimônio cultural que representa:</label><br>
						" . $CNPC->dropdown_segmentos( get_bloginfo( 'title' ), 'segmento', $segmento ) . "
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<td colspan='2'>
						<button type='submit'>Salvar Dados</button>
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<th colspan='2'>Anexos</th>
				</tr>

				<tr>
					<td colspan='2'>Dúvidas sobre como converter seu arquivo para .pdf, leia o <a href='http://www.cultura.gov.br/setoriais/converter-documento-para-pdf/' target='_blank'>passo a passo para realizar a conversão</a>.
				</tr>
		";

		$comprovante_atuacao = get_user_meta( $user_ID, 'comprovante_atuacao', true );
		if( !empty( $comprovante_atuacao ) )
		{
			$attached_comprovante_atuacao  = "<input type='checkbox' name='attached_comprovante_atuacao' value='comprovante_atuacao' class='show-hide-inverse' checked='checked' /> ";
			$attached_comprovante_atuacao .= "<a href='{$comprovante_atuacao}' target='_blank'>{$comprovante_atuacao}</a><br>";
		}

		$output .= "
				<tr>
					<td colspan='2'>
						<label for='comprovante_atuacao'>Comprovação de Três Anos de Atuação no Setor: *</label><br>
						{$attached_comprovante_atuacao}
						<input type='file' id='comprovante_atuacao' name='comprovante_atuacao' /> <button type='submit'>Salvar Anexo</button><br>
						<small>Curriculo ou Diploma Profissional ou Registro Profissional no Ministério do Trabalho (DRT) ou Participação em Entidade/Comunidade representativa da área ou segmento no formato .pdf com no máximo 1MB.</small>
					</td>
				</tr>
		";

		$comprovante_identidade = get_user_meta( $user_ID, 'comprovante_identidade', true );
		if( !empty( $comprovante_identidade ) )
		{
			$attached_comprovante_identidade  = "<input type='checkbox' name='attached_comprovante_identidade' value='comprovante_identidade' class='show-hide-inverse' checked='checked' /> ";
			$attached_comprovante_identidade .= "<a href='{$comprovante_identidade}' target='_blank'>{$comprovante_identidade}</a><br>";
		}

		$output .= "
				<tr>
					<td colspan='2'>
						<label for='comprovante_identidade'>Identidade: *</label><br>
						{$attached_comprovante_identidade}
						<input type='file' id='comprovante_identidade' name='comprovante_identidade' /> <button type='submit'>Salvar Anexo</button><br>
						<small>Apenas arquivos no formato .pdf com no máximo 1MB</small>
					</td>
				</tr>
		";

		$comprovante_cpf = get_user_meta( $user_ID, 'comprovante_cpf', true );
		if( !empty( $comprovante_cpf ) )
		{
			$attached_comprovante_cpf  = "<input type='checkbox' name='attached_comprovante_cpf' value='comprovante_cpf' class='show-hide-inverse' checked='checked' /> ";
			$attached_comprovante_cpf .= "<a href='{$comprovante_cpf}' target='_blank'>{$comprovante_cpf}</a><br>";
		}

		$output .= "
				<tr>
					<td colspan='2'>
						<label for='comprovante_cpf'>CPF: *</label><br>
						{$attached_comprovante_cpf}
						<input type='file' id='comprovante_cpf' name='comprovante_cpf' /> <button type='submit'>Salvar Anexo</button><br>
						<small>Apenas arquivos no formato .pdf com no máximo 1MB</small>
					</td>
				</tr>
		";

		$comprovante_residencia = get_user_meta( $user_ID, 'comprovante_residencia', true );
		if( !empty( $comprovante_residencia ) )
		{
			$attached_comprovante_residencia  = "<input type='checkbox' name='attached_comprovante_residencia' value='comprovante_residencia' class='show-hide-inverse' checked='checked' /> ";
			$attached_comprovante_residencia .= "<a href='{$comprovante_residencia}' target='_blank'>{$comprovante_residencia}</a><br>";
		}

		$output .= "
				<tr>
					<td colspan='2'>
						<label for='comprovante_residencia'>Comprovante de Residência: *</label><br>
						{$attached_comprovante_residencia}
						<input type='file' id='comprovante_residencia' name='comprovante_residencia' /> <button type='submit'>Salvar Anexo</button><br>
						<small>Apenas arquivos no formato .pdf com no máximo 2MB</small>
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<th colspan='2'>Declaração</th>
				</tr>

				<tr>
					<td colspan='2'>
						<strong>Declaro para os devidos fins que:</strong><br>
						<label><input type='checkbox' name='declaracao_veracidade' value='declaracao_veracidade' {$declaracao_veracidade}> São verdadeiras todas as informações contidas neste formulário, bem como  na documentação encaminhada nos respectivos  anexos,  e que estou ciente que constitui crime de falsidade ideológica  a omissão de declaração em documento público ou a inserção de declaração falsa da que devia constar, com o fim de alterar a verdade sobre o fato,  juridicamente relevante (artigo 299 do Código Penal Brasileiro); *</label>
						<label><input type='checkbox' name='declaracao_acompanhamento' value='declaracao_acompanhamento' {$declaracao_acompanhamento}> Estou ciente de que deverei acompanhar  os procedimentos para  aprovação do cadastro de eleitor e registro de candidatura por meio do sítio eletrônico do Ministério da Cultura, conforme artigo 18 da Portaria nº  51, de 02 de maio de 2012; *</label>
						<label><input type='checkbox' name='declaracao_pnc' value='declaracao_pnc' {$declaracao_pnc}> Tenho conhecimento do <a href='http://pnc.culturadigital.br/' title='Plano Nacional de Cultura' target='_blank'>Plano Nacional de Cultura</a>; *</label>
						<label><input type='checkbox' name='declaracao_das' value='declaracao_das' {$declaracao_das} class='show-hide-inverse'> Não sou detentor de cargo comissionado na administração pública federal, estadual, distrital ou municipal.</label>
					</td>
				</tr>

				<tr>
					<td colspan='2'>
						<table id='declaracao_das' class='dark'>
							<tr>
								<th colspan='2'>Dados do Comissionado</th>
							</tr>

							<tr colspan='2'>
								<td>
									<label for='cargo'>Cargo: *</label><br>
									<input type='text' id='cargo' name='cargo' value='{$cargo}' size='30' maxlength='100' />
								</td>
							</tr>

							<tr>
								<th colspan='2'>Anexos</th>
							</tr>
		";

		$comprovante_comissionado = get_user_meta( $user_ID, 'comprovante_comissionado', true );
		if( !empty( $comprovante_comissionado ) )
		{
			$attached_comprovante_comissionado  = "<input type='checkbox' name='attached_comprovante_comissionado' value='comprovante_comissionado' class='show-hide-inverse' checked='checked' /> ";
			$attached_comprovante_comissionado .= "<a href='{$comprovante_comissionado}' target='_blank'>{$comprovante_comissionado}</a><br>";
		}

		$output .= "
							<tr>
								<td colspan='2'>
									<label for='comprovante_comissionado'>Comprovante de Função na Entidade Civil: *</label><br>
									{$attached_comprovante_comissionado}
									<input type='file' id='comprovante_comissionado' name='comprovante_comissionado' /> <button type='submit'>Salvar Anexo</button><br>
									<small>Na hipótese de eleitor que seja representante da sociedade civil e ocupante de cargo em comissão, a declaração de que trata o inciso VII do art. 16 será substituída por informação que individualize o cargo comissionado que ocupa, acompanhada de comprovação da função que exerce na entidade civil que representa no formato .pdf com no máximo 1MB.</small>
								</td>
							</tr>
		";

		$output .= "
						</table>
					</td>
				</tr>
		";



		$output .= "
				<tr>
					<td colspan='2'>
						<button type='submit' onclick='return confirm( 'Confira atentamente seus dados e arquivos anexados!' );'>Cadastrar</button>
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<th colspan='2' class='post-title'>Cadastro de Candidato</th>
				</tr>
		";

		$output .= "
				<tr>
					<td colspan='2'>
						<label><input type='checkbox' name='candidatura' value='candidatura' class='show-hide' {$candidatura}> Desejo me candidatar a essa setorial</label><br>
						<small>Para inscrever sua candidatura é necessário preencher o cadastro de Eleitor.</small>
					</td>
				</tr>
		";

		$output .= "
				<tr>
					<td colspan='2'>
						<table id='candidatura' class='dark'>
		";

		$output .= "
					<tr>
						<th colspan='2'>Dados do Candidato</th>
					</tr>

					<tr>
						<td colspan='2'>
							<label for='propostas'>Carta Programa: *</label><br>
							<textarea id='propostas' name='propostas' cols='100' rows='10' maxlength='3000' class='limit-chars'>{$dados_candidatura[ 'propostas' ]}</textarea><br>
							<small>A carta programa deve conter pelo menos três propostas de diretrizes para o desenvolvimento da área em que concorre.</small>
						</td>
					</tr>
		";

		$output .= "
					<tr>
						<th colspan='2'>Anexos</th>
					</tr>
		";

		if( !empty( $dados_candidatura[ 'curriculo' ] ) )
		{
			$attached_curriculo  = "<input type='checkbox' name='attached_curriculo' value='curriculo' class='show-hide-inverse' checked='checked' /> ";
			$attached_curriculo .= "<a href='{$dados_candidatura[ 'curriculo' ]}' target='_blank'>{$dados_candidatura[ 'curriculo' ]}</a><br>";
		}

		$output .= "
					<tr>
						<td colspan='2'>
							<label for='curriculo'>Comprovação de Atuação Cultural: *</label><br>
							{$attached_curriculo}
							<input type='file' id='curriculo' name='curriculo' /> <button type='submit'>Salvar Anexo</button><br>
							<small>Curriculo detalhado com comprovada atuação nos últimos três anos no formato .pdf com no máximo 2MB.</small>
						</td>
					</tr>
		";

		if( !empty( $dados_candidatura[ 'portfolio' ] ) )
		{
			$attached_portfolio  = "<input type='checkbox' name='attached_portfolio' value='portfolio' class='show-hide-inverse' checked='checked' /> ";
			$attached_portfolio .= "<a href='{$dados_candidatura[ 'portfolio' ]}' target='_blank'>{$dados_candidatura[ 'portfolio' ]}</a><br>";
		}

		$output .= "
					<tr>
						<td colspan='2'>
							<label for='portfolio'>Porfólio:</label><br>
							{$attached_portfolio}
							<input type='file' id='portfolio' name='portfolio' /> <button type='submit'>Salvar Anexo</button><br>
							<small>Apenas arquivos no formato .pdf com no máximo 2MB</small>
						</td>
					</tr>
		";

		if( !empty( $dados_candidatura[ 'apoio' ] ) )
		{
			$attached_apoio  = "<input type='checkbox' name='attached_apoio' value='apoio' class='show-hide-inverse' checked='checked' /> ";
			$attached_apoio .= "<a href='{$dados_candidatura[ 'apoio' ]}' target='_blank'>{$dados_candidatura[ 'apoio' ]}</a><br>";
		}

		$output .= "
					<tr>
						<td colspan='2'>
							<label for='apoio'>Carta de Apoio: *</label><br>
							{$attached_apoio}
							<input type='file' id='apoio' name='apoio' /> <button type='submit'>Salvar Anexo</button><br>
							<small>Carta de apoio subscrita por entidade com atuação na área em que concorre ou pelo menos dez eleitores da mesma área, cujo cadastro eleitoral venha a ser devidamente validado no formato .pdf com no máximo 1MB.</small>
						</td>
					</tr>
		";

		$output .= "
				<tr>
					<th colspan='2'>Declaração</th>
				</tr>

				<tr>
					<td colspan='2'>
						<strong>Declaro para os devidos fins que:</strong><br>
						<label><input type='checkbox' name='declaracao_candidato_veracidade' value='declaracao_candidato_veracidade' {$declaracao_candidato_veracidade}> São verdadeiras todas as informações contidas neste formulário, bem como  na documentação encaminhada nos respectivos  anexos,  e que estou ciente que constitui crime de falsidade ideológica  a omissão de declaração em documento público ou a inserção de declaração falsa da que devia constar, com o fim de alterar a verdade sobre o fato,  juridicamente relevante (artigo 299 do Código Penal Brasileiro). *</label>
					</td>
				</tr>
		";

		$output .= "
					<tr>
						<td colspan='2'>
							<button type='submit' onclick='return confirm( 'Confira atentamente seus dados e arquivos anexados!' );'>Candidatar</button>
						</td>
					</tr>
		";

		$output .= "
						</table>
					</td>
				</tr>
		";

		$output .= "
			<tr>
				<td><small>* preenchimento obrigatório</small></td>
			</tr>
		";

		$output .= "</table>";

		$output .= "</form>";

		$output .= '</div>';

		return $output;
	}

	/**
	 * manage pessoa fisica
	 *
	 * @name    cnpc_pessoa_fisica
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-04-30
	 * @return  string
	 */
	function cnpc_pessoa_fisica()
	{
		// verificar se o usuário já está cadastrado
		if( current_user_can( 'cnpc' ) )
			$this->update_pessoa_fisica();
		else
			$this->insert_pessoa_fisica();
	}

	/**
	 * insert user by cpf
	 *
	 * @name    insert_pessoa_fisica
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-07-30
	 * @return  string
	 */
	function insert_pessoa_fisica()
	{
		global $CNPC, $user_ID;

		// verificar se as inscrições estão abertas
		if( !$CNPC->inscricoes_abertas() )
			return false;

		// verificar se o envio foi feito pelo formulário identificado
		if( !wp_verify_nonce( $_POST[ 'nonce' ], 'cnpc_pessoa_fisica' ) )
			return false;

		$Validator = new Validator();

		$nascimento = array( preg_replace( '/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$2-$1', $_POST[ 'nascimento' ] ) );

		// validate dados acesso
		$login   = $Validator->validate( $_POST[ 'cpf' ],     'CPF',     'required=1&cpf=1' );
		$senha   = $Validator->validate( $_POST[ 'senha' ],   'Senha',   'required=1&max_length=100' );
		$email   = $Validator->validate( $_POST[ 'email' ],   'E-mail',  'required=1&email=1&max_length=100' );

		$dados_pessoais[ 'nome' ]          = $Validator->validate( $_POST[ 'nome' ],         'Nome',         'required=1&max_length=100' );
		$dados_pessoais[ 'apelido' ]       = $Validator->validate( $_POST[ 'apelido' ],      'Apelido',      'required=0&max_length=100' );

		// verificar erros
		$CNPC->update_error( $Validator->error() );

		if( $CNPC->have_error() )
			return false;

		// cadastrar usuário
		$user = array(
			'user_login'    => $login,
			'user_pass'     => $senha,
			'user_email'    => $email,
			'first_name'    => $dados_pessoais[ 'nome' ],
			'nickname'      => $dados_pessoais[ 'apelido' ],
			'display_name'  => $dados_pessoais[ 'nome' ],
			'user_nicename' => sanitize_title( $dados_pessoais[ 'nome' ] ),
			'role'          => 'participante'
		);

		$new_user_id = wp_insert_user( $user );

		if( is_wp_error( $new_user_id ) )
			$CNPC->update_error( $new_user_id->get_error_message() );

		// autenticar usuário
		$creds = array( 'user_login' => $login, 'user_password' => $senha, 'remember' => true );

		$user = wp_signon( $creds, false );

		if( is_wp_error( $user ) )
			$CNPC->update_error( $user->get_error_message() );

		// validate dados pessoais
		$dados_pessoais[ 'rg' ]            = $Validator->validate( $_POST[ 'rg' ],           'RG',           'required=1&numeric=1&max_length=15' );
		$dados_pessoais[ 'nascimento' ]    = $Validator->validate( $nascimento[ 0 ],         'Nascimento',   'required=1&length=10' );
		$dados_pessoais[ 'naturalidade' ]  = $Validator->validate( $_POST[ 'naturalidade' ], 'Naturalidade', 'required=1&max_length=100' );
		$dados_pessoais[ 'nacionalidade' ] = 'Brasil';
		$dados_pessoais[ 'etnia' ]         = $Validator->validate( $_POST[ 'etnia' ],        'Etnia',        'required=0&max_length=100' );

		// validate dados geográficos
		$dados_geograficos[ 'endereco' ]    = $Validator->validate( $_POST[ 'endereco' ],    'Endereco',    'required=1&max_length=100' );
		$dados_geograficos[ 'complemento' ] = $Validator->validate( $_POST[ 'complemento' ], 'Complemento', 'required=0&max_length=100' );
		$dados_geograficos[ 'cep' ]         = $Validator->validate( $_POST[ 'cep' ],         'CEP',         'required=1&numeric=1&length=8' );
		$dados_geograficos[ 'bairro' ]      = $Validator->validate( $_POST[ 'bairro' ],      'Bairro',      'required=1&max_length=100' );
		$dados_geograficos[ 'cidade' ]      = $Validator->validate( $_POST[ 'cidade' ],      'Cidade',      'required=1&max_length=100' );
		$dados_geograficos[ 'estado' ]      = $Validator->validate( $_POST[ 'estado' ],      'Estado',      'required=1&length=2' );
		$dados_geograficos[ 'pais' ]        = 'Brasil';

		// validate dados profissionais
		$dados_profissionais[ 'formacao' ]  = $Validator->validate( $_POST[ 'formacao' ],                       'Formação',  'required=1&max_length=100' );
		$dados_profissionais[ 'atuacao' ]   = $Validator->validate( $_POST[ 'atuacao' ],                        'Atuação',   'required=1&max_length=100' );
		$dados_profissionais[ 'biografia' ] = $Validator->validate( $CNPC->clear_text( $_POST[ 'biografia' ] ), 'Biografia', 'required=1&max_length=3000' );

		$segmento                           = $Validator->validate( 'segmento', 'Segmento', 'required=0&max_length=250' );

		// validate anexos
		$comprovante_atuacao      = $Validator->validate( $CNPC->upload_anexo( 'comprovante_atuacao',      'Comprovante de Três Anos de Atuação Cultural' ), 'Comprovante de Três Anos de Atuação Cultural', 'required=1' );
		$comprovante_identidade   = $Validator->validate( $CNPC->upload_anexo( 'comprovante_identidade',   'Comprovante de Identidade' ),                    'Comprovante de Identidade',                    'required=1' );
		$comprovante_cpf          = $Validator->validate( $CNPC->upload_anexo( 'comprovante_cpf',          'Comprovante de CPF' ),                           'Comprovante de CPF',                           'required=1' );
		$comprovante_residencia   = $Validator->validate( $CNPC->upload_anexo( 'comprovante_residencia',   'Comprovante de Residência', '2100000' ),         'Comprovante de Residência',                    'required=1' );

		// verificar se esse usuário é candidato
		$candidatura = $Validator->validate( $_POST[ 'candidatura' ], 'Candidatura', 'required=0' );

		// validate declaracao
		$declaracao_veracidade = $Validator->validate( $_POST[ 'declaracao_veracidade' ], 'Declaração de Veracidade', 'required=1' );
		$declaracao_acompanhamento = $Validator->validate( $_POST[ 'declaracao_acompanhamento' ], 'Declaração de Acompanhamento', 'required=1' );
		$declaracao_pnc = $Validator->validate( $_POST[ 'declaracao_pnc' ], 'Declaração de Conhecimento do PNC', 'required=1' );
		$declaracao_das = $Validator->validate( $_POST[ 'declaracao_das' ], 'Declaração de Cargo Comissionado', 'required=0' );

		if( empty( $declaracao_das ) )
		{
			// validate dados do comissionado
			$cargo                    = $Validator->validate( 'cargo', 'Cargo', 'required=1&max_length=100' );

			// validate anexos
			$comprovante_comissionado = $Validator->validate( $CNPC->upload_anexo( 'comprovante_comissionado', 'Comprovante de Vínculo Comissionado' ), 'Comprovante de Vínculo Comissionado', 'required=1' );
		}

		if( 'candidatura' == $candidatura )
		{
			// validate dados candidatura
			$dados_candidatura[ 'propostas' ] = $Validator->validate( $CNPC->clear_text( $_POST[ 'propostas' ] ), 'Carta Programa', 'required=1&max_length=3000' );

			// validate anexos
			$dados_candidatura[ 'curriculo' ] = $Validator->validate( $CNPC->upload_anexo( 'curriculo', 'Curriculo Detalhado', '2100000' ), 'Curriculo Detalhado', 'required=1' );
			$dados_candidatura[ 'portfolio' ] = $Validator->validate( $CNPC->upload_anexo( 'portfolio', 'Portfólio', '2100000' ), 'Portfólio', 'required=0' );
			$dados_candidatura[ 'apoio' ]     = $Validator->validate( $CNPC->upload_anexo( 'apoio', 'Carta de Apoio' ), 'Carta de Apoio', 'required=1' );

			// validate declaracao
			$declaracao_candidato_veracidade = $Validator->validate( $_POST[ 'declaracao_candidato_veracidade' ], 'Declaração de Veracidade', 'required=1' );
		}

		// verificar erros
		$CNPC->update_error( $Validator->error() );

		//wp_new_user_notification( $new_user_id, $senha );

		// cadastrar informações do eleitor
		$dados_pessoais[ 'login' ]      = $login;
		$dados_geograficos[ 'login' ]   = $login;
		$dados_profissionais[ 'login' ] = $login;

		$CNPC->update_dados_pessoais( $dados_pessoais );
		$CNPC->update_dados_geograficos( $dados_geograficos );
		$CNPC->update_dados_profissionais( $dados_profissionais );

		update_user_meta( $new_user_id, 'segmento', $segmento );
		update_user_meta( $new_user_id, 'estado', $dados_geograficos[ 'estado' ] );

		// update anexos
		update_user_meta( $new_user_id, 'comprovante_atuacao', $comprovante_atuacao );
		update_user_meta( $new_user_id, 'comprovante_identidade', $comprovante_identidade );
		update_user_meta( $new_user_id, 'comprovante_cpf', $comprovante_cpf );
		update_user_meta( $new_user_id, 'comprovante_residencia', $comprovante_residencia );

		// update declaracoes
		update_user_meta( $new_user_id, 'declaracao_veracidade', $declaracao_veracidade );
		update_user_meta( $new_user_id, 'declaracao_acompanhamento', $declaracao_acompanhamento );
		update_user_meta( $new_user_id, 'declaracao_pnc', $declaracao_pnc );
		update_user_meta( $new_user_id, 'declaracao_das', $declaracao_das );

		// cadastrar informações comissionados
		if( empty( $declaracao_das ) )
		{
			update_user_meta( $new_user_id, 'cargo', $cargo );
			update_user_meta( $new_user_id, 'comprovante_comissionado', $comprovante_comissionado );
		}

		// update candidatura
		update_user_meta( $new_user_id, 'candidatura', $candidatura );

		// cadastrar informações do candidato
		if( 'candidatura' == $candidatura )
		{
			$dados_candidatura[ 'login' ] = $login;

			$CNPC->update_dados_candidatura( $dados_candidatura );

			// update declaracoes
			update_user_meta( $new_user_id, 'declaracao_candidato_veracidade', $declaracao_candidato_veracidade );
		}

		// redirecionar usuário
		wp_redirect( "{$_POST[ 'redirect' ]}?sussa=1" ); exit();
	}

	/**
	 * update user by cpf
	 *
	 * @name    update_pessoa_fisica
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-04-30
	 * @updated 2012-06-19
	 * @return  string
	 */
	function update_pessoa_fisica()
	{
		global $CNPC, $user_ID, $user_login;

		// verificar se as inscrições estão abertas
		if( !$CNPC->inscricoes_abertas() )
			return false;

		// verificar se o envio foi feito pelo formulário identificado
		if( !wp_verify_nonce( $_POST[ 'nonce' ], 'cnpc_pessoa_fisica' ) )
			return false;

		// verifica se o usuário tem permissão para alterar esses dados
		if( !current_user_can( 'cnpc' ) )
			return false;

		$Validator = new Validator();

		$nascimento = array( preg_replace( '/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', '$3-$2-$1', $_POST[ 'nascimento' ] ) );

		$datetime_nascimento = strtotime( $nascimento[ 0 ] );
		$datetime_limite     = strtotime( '1994-07-02' );

		// verificar idade
		if( $datetime_nascimento > $datetime_limite )
			$CNPC->update_error( "A idade mínima para participação das setoriais é 18 anos." );

		// validate dados pessoais
		$dados_pessoais[ 'rg' ]           = $Validator->validate( $_POST[ 'rg' ],      'RG',           'required=1&numeric=1&max_length=15' );
		$dados_pessoais[ 'nome' ]         = $Validator->validate( $_POST[ 'nome' ],    'Nome',         'required=1&max_length=100' );
		$dados_pessoais[ 'apelido' ]      = $Validator->validate( $_POST[ 'apelido' ], 'Apelido',      'required=0&max_length=100' );
		$dados_pessoais[ 'nascimento' ]   = $Validator->validate( $nascimento[ 0 ],    'Nascimento',   'required=1&length=10' );
		$dados_pessoais[ 'naturalidade' ] = $Validator->validate( $_POST[ 'naturalidade' ],  'Naturalidade', 'required=0&max_length=100' );
		$dados_pessoais[ 'etnia' ]        = $Validator->validate( $_POST[ 'etnia' ],   'Etnia',        'required=0&max_length=100' );

		// validate dados geográficos
		$dados_geograficos[ 'endereco' ]    = $Validator->validate( $_POST[ 'endereco' ],    'Endereco',    'required=1&max_length=100' );
		$dados_geograficos[ 'complemento' ] = $Validator->validate( $_POST[ 'complemento' ], 'Complemento', 'required=1&max_length=100' );
		$dados_geograficos[ 'bairro' ]      = $Validator->validate( $_POST[ 'bairro' ],      'Bairro',      'required=1&max_length=100' );
		$dados_geograficos[ 'cep' ]         = $Validator->validate( $_POST[ 'cep' ],         'CEP',         'required=1&numeric=1&length=8' );
		$dados_geograficos[ 'cidade' ]      = $Validator->validate( $_POST[ 'cidade' ],      'Cidade',      'required=1&max_length=100' );
		$dados_geograficos[ 'estado' ]      = $Validator->validate( $_POST[ 'estado' ],      'Estado',      'required=1&length=2' );
		$dados_geograficos[ 'pais' ]        = 'Brasil';

		// validate dados profissionais
		$dados_profissionais[ 'formacao' ]  = $Validator->validate( $_POST[ 'formacao' ],                       'Formação',     'required=1&max_length=100' );
		$dados_profissionais[ 'atuacao' ]   = $Validator->validate( $_POST[ 'atuacao' ],                        'Atuação',      'required=1&max_length=100' );
		$dados_profissionais[ 'biografia' ] = $Validator->validate( $CNPC->clear_text( $_POST[ 'biografia' ] ), 'Apresentação', 'required=0&max_length=3000' );

		$segmento                           = $Validator->validate( $_POST[ 'segmento' ], 'Segmento', 'required=0&max_length=250' );

		// validate anexos
		if( empty( $_POST[ 'attached_comprovante_atuacao' ] ) )
		{
			$comprovante_atuacao = $Validator->validate( $CNPC->upload_anexo( 'comprovante_atuacao', 'Comprovante de Atuação Cultural' ), 'Comprovante de Atuação Cultural', 'required=1' );

			update_user_meta( $user_ID, 'comprovante_atuacao', $comprovante_atuacao );
		}

		if( empty( $_POST[ 'attached_comprovante_identidade' ] ) )
		{
			$comprovante_identidade = $Validator->validate( $CNPC->upload_anexo( 'comprovante_identidade', 'Comprovante de Identidade' ), 'Comprovante de Identidade', 'required=1' );

			update_user_meta( $user_ID, 'comprovante_identidade', $comprovante_identidade );
		}

		if( empty( $_POST[ 'attached_comprovante_cpf' ] ) )
		{
			$comprovante_cpf = $Validator->validate( $CNPC->upload_anexo( 'comprovante_cpf', 'Comprovante de CPF' ), 'Comprovante de CPF', 'required=1' );

			update_user_meta( $user_ID, 'comprovante_cpf', $comprovante_cpf );
		}

		if( empty( $_POST[ 'attached_comprovante_residencia' ] ) )
		{
			$comprovante_residencia = $Validator->validate( $CNPC->upload_anexo( 'comprovante_residencia', 'Comprovante de Residência', '2100000' ), 'Comprovante de Residência', 'required=1' );

			update_user_meta( $user_ID, 'comprovante_residencia', $comprovante_residencia );
		}

		// validate declaracao
		$declaracao_veracidade = $Validator->validate( $_POST[ 'declaracao_veracidade' ], 'Declaração de Veracidade', 'required=1' );
		$declaracao_acompanhamento = $Validator->validate( $_POST[ 'declaracao_acompanhamento' ], 'Declaração de Acompanhamento', 'required=1' );
		$declaracao_pnc = $Validator->validate( $_POST[ 'declaracao_pnc' ], 'Declaração de Conhecimento do PNC', 'required=1' );
		$declaracao_das = $Validator->validate( $_POST[ 'declaracao_das' ], 'Declaração de Cargo Comissionado', 'required=0' );

		if( empty( $declaracao_das ) )
		{
			// validate dados comissionado
			$cargo = $Validator->validate( $_POST[ 'cargo' ], 'Cargo', 'required=1&max_length=100' );

			if( empty( $_POST[ 'attached_comprovante_comissionado' ] ) )
			{
				$comprovante_comissionado = $Validator->validate( $CNPC->upload_anexo( 'comprovante_comissionado', 'Comprovante de Vínculo Comissionado' ), 'Comprovante de Vínculo Comissionado', 'required=0' );

				update_user_meta( $user_ID, 'comprovante_comissionado', $comprovante_comissionado );
			}
		}

		// verificar se esse usuário é candidato
		$candidatura = $Validator->validate( $_POST[ 'candidatura' ], 'Candidatura', 'required=0' );

		if( 'candidatura' == $candidatura )
		{
			// validate dados candidatura
			$dados_candidatura[ 'propostas' ] = $Validator->validate( $CNPC->clear_text( $_POST[ 'propostas' ] ), 'Carta Programa', 'required=1&max_length=3000' );

			if( empty( $_POST[ 'attached_curriculo' ] ) )
				$dados_candidatura[ 'curriculo' ] = $Validator->validate( $CNPC->upload_anexo( 'curriculo', 'Curriculo Detalhado', '2100000' ), 'Curriculo Detalhado', 'required=1' );

			if( empty( $_POST[ 'attached_portfolio' ] ) )
				$dados_candidatura[ 'portfolio' ] = $Validator->validate( $CNPC->upload_anexo( 'portfolio', 'Portfólio', '2100000' ), 'Portfólio', 'required=0' );

			if( empty( $_POST[ 'attached_apoio' ] ) )
				$dados_candidatura[ 'apoio' ] = $Validator->validate( $CNPC->upload_anexo( 'apoio', 'Carta de Apoio' ), 'Carta de Apoio', 'required=1' );

			// validate declaracao
			$declaracao_candidato_veracidade = $Validator->validate( $_POST[ 'declaracao_candidato_veracidade' ], 'Declaração de Veracidade', 'required=1' );
		}

		// verificar erros
		$CNPC->update_error( $Validator->error() );

		if( empty( $user_ID ) )
			return false;

		// cadastrar usuário
		$user = array(
			'ID'            => $user_ID,
			'first_name'    => $dados_pessoais[ 'nome' ],
			'nickname'      => $dados_pessoais[ 'apelido' ],
			'display_name'  => $dados_pessoais[ 'nome' ],
			'user_nicename' => sanitize_title( $dados_pessoais[ 'nome' ] ),
		);

		$new_user_id = wp_update_user( $user );

		if( is_wp_error( $new_user_id ) )
			$CNPC->update_error( $new_user_id->get_error_message() );

		// cadastrar informações do eleitor
		$dados_pessoais[ 'login' ]      = $user_login;
		$dados_geograficos[ 'login' ]   = $user_login;
		$dados_profissionais[ 'login' ] = $user_login;

		$CNPC->update_dados_pessoais( $dados_pessoais);
		$CNPC->update_dados_geograficos( $dados_geograficos );
		$CNPC->update_dados_profissionais( $dados_profissionais );

		update_user_meta( $user_ID, 'segmento', $segmento );
		update_user_meta( $user_ID, 'estado', $dados_geograficos[ 'estado' ] );

		// update declaracao
		update_user_meta( $user_ID, 'declaracao_veracidade', $declaracao_veracidade );
		update_user_meta( $user_ID, 'declaracao_acompanhamento', $declaracao_acompanhamento );
		update_user_meta( $user_ID, 'declaracao_pnc', $declaracao_pnc );
		update_user_meta( $user_ID, 'declaracao_das', $declaracao_das );

		if( empty( $declaracao_das ) )
		{
			update_user_meta( $user_ID, 'cargo', $cargo );
		}

		// update candidatura
		update_user_meta( $user_ID, 'candidatura', $candidatura );

		// cadastrar informações do candidato
		if( 'candidatura' == $candidatura )
		{
			$dados_candidatura[ 'login' ] = $user_login;

			$CNPC->update_dados_candidatura( $dados_candidatura );

			// update declaracao
			update_user_meta( $user_ID, 'declaracao_candidato_veracidade', $declaracao_candidato_veracidade );
		}

		if( $CNPC->have_error() )
			return false;

		// redirecionar usuário
		wp_redirect( "{$_POST[ 'redirect' ]}?sussa=2" ); exit();
	}

	/**
	 * shortcode: contador
	 *
	 * @name    shortcode_contador
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-07-05
	 * @updated 2012-07-05
	 * @return  string
	 */
	function shortcode_contador( $args )
	{
		global $wpdb;

		$regions	= array(
			'Centro-Oeste' => array( 'DF', 'GO', 'MT', 'MS' ),
			'Nordeste'     => array( 'AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE' ),
			'Norte'        => array( 'AC', 'AM', 'AP', 'PA', 'RO', 'RR', 'TO' ),
			'Sudeste'      => array( 'ES', 'MG', 'RJ', 'SP' ),
			'Sul'          => array( 'PR', 'RS', 'SC' )
		);

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

		// quantidade de inscritos
		$eleitores  = get_users( 'meta_key=candidatura&meta_value=candidatura&meta_compare=!=' );
		$candidatos = get_users( 'meta_key=candidatura&meta_value=candidatura' );

		//$participantes = get_users( 'meta_key=candidatura' );

		// quantidade de inscritos por estado
		$meta       = $wpdb->get_blog_prefix() . 'capabilities';
		$inscritos  = $wpdb->get_col( $wpdb->prepare( "SELECT g.estado AS estado FROM {$wpdb->users} AS u LEFT JOIN {$wpdb->usermeta} AS um ON ( u.ID = um.user_id ) LEFT JOIN {$wpdb->cnpc_dados_geograficos} AS g ON ( u.user_login = g.login ) WHERE um.meta_key = %s", $meta ) );

				
		$quantidade_por_estado = array();
		$quantidade_por_regiao = array();

		foreach( $inscritos as $estado )
			$quantidade_por_estado[ $estado ] = $quantidade_por_estado[ $estado ] + 1;

		?>
			<style type="text/css">
				table th, table td {
					padding: 3px;
				}

				.inscritos .odd {
					background: #EFEDE6;
				}

				.inscritos .out {
					color: #FF0000;
				}

				.inscritos .total td {
					border-top: 1px solid #000000;
				}
			</style>

			<div class="error">
				<p>Os estados em vermelho ainda não garantiram uma vaga nos Fóruns Nacionais Setoriais por não atingir o quórum mínimo de 5 participantes.</p>
			</div>

			<div class="inscritos">
				<?php foreach( $regions as $regiao => $estados ) : ?>
					<h1><?php print $regiao; ?></h1>

					<table width="100%" cellspacing="0">
						<?php foreach( $estados as $estado ) : ?>
							<?php if( empty( $estado ) ) continue; ?>
							<?php $quantidade_por_regiao[ $regiao ] = $quantidade_por_regiao[ $regiao ] + $quantidade_por_estado[ $estado ] ?>
							<tr class="<?php if( $odd = !$odd ) print 'odd'; ?> <?php if( $quantidade_por_estado[ $estado ] < 5 ) print 'out'; ?>">
								<td><?php print $states[ $estado ]; ?></td>
								<td width="10%" align="center"><strong><?php print ( $quantidade_por_estado[ $estado ] ) ? $quantidade_por_estado[ $estado ] : '0'; ?></strong></td>
							</tr>
						<?php endforeach; ?>
						<tr class='total'>
							<td align="right"><strong>Total</strong></td>
							<td width="10%" align="center"><strong><?php print ( $quantidade_por_regiao[ $regiao ] ) ? $quantidade_por_regiao[ $regiao ] : '0'; ?></strong></td>
						</tr>
					</table>
				<?php endforeach; ?>

				<br clear="all" />
			</div>
		<?php
	}

	// CONSTRUCTOR ///////////////////////////////////////////////////////////////////////////////////
	/**
	 * @name    CNPC_Cadastro
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-19
	 * @updated 2012-07-05
	 * @return  void
	 */
	function CNPC_Cadastro()
	{
		// shortcodes
		add_shortcode( 'pessoa_fisica', array( &$this, 'shortcode_pessoa_fisica' ) );
		add_shortcode( 'contador', array( &$this, 'shortcode_contador' ) );

		// cadastro de eleitor
		add_action( 'init', array( &$this, 'cnpc_pessoa_fisica' ) );
	}

	// DESTRUCTOR ////////////////////////////////////////////////////////////////////////////////////

}

$CNPC_Cadastro = new CNPC_Cadastro();

?>