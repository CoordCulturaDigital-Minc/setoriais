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
 * Function Name: Custom Theme
 * Function URI: http://marcelomesquita.com/
 * Description: Allow the user to chose the disposal and visibility of some itens of the theme
 * Author: Marcelo Mesquita
 * Author URI: http://marcelomesquita.com/
 * Version: 0.1
 */

class Custom_Theme
{
  // ATRIBUTES ////////////////////////////////////////////////////////////////////////////////////

  // METHODS //////////////////////////////////////////////////////////////////////////////////////
  /**
	 * add administrative menus
	 *
	 * @name    menus
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-29
	 * @updated 2012-03-29
	 * @return  string
	 */
  function menus()
  {
    add_theme_page( 'Personalizar Tema', 'Personalizar Tema', 'edit_themes', 'custom_theme', array( &$this, 'configure_theme' ) );
  }

  /**
	 * configure theme
	 *
	 * @name    configure_theme
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-29
	 * @updated 2012-05-02
	 * @return  string
	 */
  function configure_theme()
  {
    // Save options
    if( wp_verify_nonce( $_POST[ 'nonce' ], 'theme_options' ) )
		{
      // slideshow
      $theme_options[ 'cycle' ]              = ( int ) $_POST[ 'cycle' ];

			// index infos
      $theme_options[ 'index_thumb' ]         = ( bool ) $_POST[ 'index_thumb' ];
			$theme_options[ 'index_excerpt' ]       = ( bool ) $_POST[ 'index_excerpt' ];
			$theme_options[ 'index_date' ]          = ( bool ) $_POST[ 'index_date' ];
			$theme_options[ 'index_modified_date' ] = ( bool ) $_POST[ 'index_modified_date' ];
      $theme_options[ 'index_author' ]        = ( bool ) $_POST[ 'index_author' ];
      $theme_options[ 'index_category' ]      = ( bool ) $_POST[ 'index_category' ];
      $theme_options[ 'index_tag' ]           = ( bool ) $_POST[ 'index_tag' ];
      $theme_options[ 'index_comments' ]      = ( bool ) $_POST[ 'index_comments' ];

			// post infos
      $theme_options[ 'post_excerpt' ]       = ( bool ) $_POST[ 'post_excerpt' ];
			$theme_options[ 'post_date' ]          = ( bool ) $_POST[ 'post_date' ];
			$theme_options[ 'post_modified_date' ] = ( bool ) $_POST[ 'post_modified_date' ];
      $theme_options[ 'post_author' ]        = ( bool ) $_POST[ 'post_author' ];
      $theme_options[ 'post_category' ]      = ( bool ) $_POST[ 'post_category' ];
      $theme_options[ 'post_tag' ]           = ( bool ) $_POST[ 'post_tag' ];
      $theme_options[ 'post_comments' ]      = ( bool ) $_POST[ 'post_comments' ];

      // Update options
      update_option( 'theme_options', $theme_options );

      // Show message
      print '<div class="updated"><p><strong>Atualizado</strong></p></div>';
		};

    // get options
    $theme_options = get_option( 'theme_options' );

    // formulário
    ?>
      <div class="wrap">
        <h2>Personalizar Tema</h2>
        <form method="post" action="">
					<input type="hidden" name="nonce" value="<?php print wp_create_nonce( 'theme_options' ); ?>" />
          <table class="form-table">
            <tbody>
							<tr valign="top">
                <th scope="row">
									<label for="cycle"><strong>Slideshow</strong></label>
									<p class="description">escolha a categoria a ser utilizada no slideshow</p>
								</th>
                <td>
                  <?php wp_dropdown_categories( "name=cycle&show_option_none=Nenhuma&hierarchical=1&hide_empty=0&selected={$theme_options[ 'cycle' ]}" ); ?>
                </td>
              </tr>

              <tr valign="top">
                <th scope="row">
									<strong>Informações da Index</strong>
									<p class="description">marque os itens que ficarão disponíveis nos indices</p>
								</th>
                <td>
                  <label><input type="checkbox" name="index_thumb" value="1" <?php if( $theme_options[ 'index_thumb' ] ) print 'checked="checked"'; ?> /> thumb</label><br>
									<label><input type="checkbox" name="index_excerpt" value="1" <?php if( $theme_options[ 'index_excerpt' ] ) print 'checked="checked"'; ?> /> chamada</label><br>
									<label><input type="checkbox" name="index_date" value="1" <?php if( $theme_options[ 'index_date' ] ) print 'checked="checked"'; ?> /> data</label><br>
									<label><input type="checkbox" name="index_modified_date" value="1" <?php if( $theme_options[ 'index_modified_date' ] ) print 'checked="checked"'; ?> /> data de atualização</label><br>
                  <label><input type="checkbox" name="index_author" value="1" <?php if( $theme_options[ 'index_author' ] ) print 'checked="checked"'; ?> /> autor</label><br>
                  <label><input type="checkbox" name="index_category" value="1" <?php if( $theme_options[ 'index_category' ] ) print 'checked="checked"'; ?> /> categorias</label><br>
                  <label><input type="checkbox" name="index_tag" value="1" <?php if( $theme_options[ 'index_tag' ] ) print 'checked="checked"'; ?> /> tags</label><br>
                  <label><input type="checkbox" name="index_comments" value="1" <?php if( $theme_options[ 'index_comments' ] ) print 'checked="checked"'; ?> /> comentários</label><br>
                </td>
              </tr>

							<tr valign="top">
                <th scope="row">
									<strong>Informações dos Posts</strong>
									<p class="description">marque os itens que ficarão disponíveis nos posts</p>
								</th>
                <td>
                  <label><input type="checkbox" name="post_excerpt" value="1" <?php if( $theme_options[ 'post_excerpt' ] ) print 'checked="checked"'; ?> /> chamada</label><br>
									<label><input type="checkbox" name="post_date" value="1" <?php if( $theme_options[ 'post_date' ] ) print 'checked="checked"'; ?> /> data</label><br>
									<label><input type="checkbox" name="post_modified_date" value="1" <?php if( $theme_options[ 'post_modified_date' ] ) print 'checked="checked"'; ?> /> data de atualização</label><br>
                  <label><input type="checkbox" name="post_author" value="1" <?php if( $theme_options[ 'post_author' ] ) print 'checked="checked"'; ?> /> autor</label><br>
                  <label><input type="checkbox" name="post_category" value="1" <?php if( $theme_options[ 'post_category' ] ) print 'checked="checked"'; ?> /> categorias</label><br>
                  <label><input type="checkbox" name="post_tag" value="1" <?php if( $theme_options[ 'post_tag' ] ) print 'checked="checked"'; ?> /> tags</label><br>
                  <label><input type="checkbox" name="post_comments" value="1" <?php if( $theme_options[ 'post_comments' ] ) print 'checked="checked"'; ?> /> comentários</label><br>
                </td>
              </tr>
            </tbody>
          </table>
          <p class="submit">
            <button type="submit" class="button-primary">Salvar</button>
          </p>
        </form>
      </div>
    <?php
  }

  // CONSTRUCTOR //////////////////////////////////////////////////////////////////////////////////
  /**
	 * add administrative menus
	 *
	 * @name    menus
	 * @author  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
	 * @since   2012-03-29
	 * @updated 2012-03-29
	 * @return  string
	 */
  function Custom_Theme()
  {
    // ativar o menu
    add_action( 'admin_menu', array( &$this, 'menus' ) );
  }

  // DESTRUCTOR ///////////////////////////////////////////////////////////////////////////////////

}

$Custom_Theme = new Custom_Theme();

?>
