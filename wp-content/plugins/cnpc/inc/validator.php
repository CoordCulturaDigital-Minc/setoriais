<?php

/**
 * Copyright (c) 2010 Marcelo Mesquita
 *
 * Written by
 *  Marcelo Mesquita <marcelo.costa@cultura.gov.br>
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
 */

class Validator
{
  // ATRIBUTES ////////////////////////////////////////////////////////////////////////////////////
  var $error = array();

  // METHODS //////////////////////////////////////////////////////////////////////////////////////
  /**
   * description
   *
   * @name    set_error
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $error - description
   * @param   String $field - description
   * @param   mixed $args - description
   * @return  void
   */
  function set_error( $error, $field, $args = array() )
  {
    $defaults = array(
      equal_field => null,
      min_value => null,
      max_value => null,
      length => null,
      min_length => null,
      max_length => null
    );

    if( is_array( $args ) )
      $parameters = $args;
    elseif( is_object( $args ) )
      $parameters = get_object_vars( $args );
    else
      parse_str( $args, $parameters );

    $parameters = array_merge( $defaults, $parameters ); // print_r( $parameters );

    extract( $parameters );

    $error = str_replace( "{field}", $field, $error );
    $error = str_replace( "{equal_field}", $equal_field, $error );
    $error = str_replace( "{min_value}", $min_value, $error );
    $error = str_replace( "{max_value}", $max_value, $error );
    $error = str_replace( "{length}", $length, $error );
    $error = str_replace( "{min_length}", $min_length, $error );
    $error = str_replace( "{max_length}", $max_length, $error );

    array_push( $this->error, $error );
  }

  /**
   * description
   *
   * @name    error
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @return  mixed
   */
  function error()
  {
    return ( !empty( $this->error ) ) ? $this->error : false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $error - description
   * @return  mixed
   */
  function required( $value, $field, $error = "O campo {field} é de preenchimento obrigatório." )
  {
    if( !empty( $value ) )
      return $value;
    elseif( $error )
      $this->set_error( $error, $field );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $error - description
   * @return  mixed
   */
  function numeric( $value, $field, $error = "O campo {field} deve ser numérico." )
  {
    if( is_numeric( $value ) )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $error - description
   * @return  mixed
   */
  function alphabetic( $value, $field, $error = "" )
  {
    if( preg_match( '/^[a-z áéíóúàèìòùâêôãõç]+$/i', $value ) )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $error - description
   * @return  type
   */
  function alphanumeric( $value, $field, $error = "" )
  {
    if( preg_match( '/^[a-z0-9 áéíóúàèìòùâêôãõç]+$/i', $value ) )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $equal_value - description
   * @param   String $equal_field - description
   * @param   String $error - description
   * @return  mixed
   */
  function equal( $value, $field, $equal_value, $equal_field, $error = "Os campos {field} e {field2} não conferem." )
  {
    if( $value == $equal_value )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field, "equal_field={$equal_field}" );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $min_value - description
   * @param   String $error - description
   * @return  mixed
   */
  function min_value( $value, $field, $min_value, $error = "O campo {field} deve ser maior ou igual a {min_value}." )
  {
    if( $value >= $min_value )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field, "min_value={$min_value}" );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $max_value - description
   * @param   String $error - description
   * @return  mixed
   */
  function max_value( $value, $field, $max_value, $error = "O campo {field} deve ser menor ou igual a {max_value}." )
  {
    if( $value <= $max_value )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field, "max_value={$max_value}" );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $min_value - description
   * @param   String $max_value - description
   * @param   String $error - description
   * @return  mixed
   */
  function between_value( $value, $field, $min_value, $max_value, $error = "O campo {field} deve ter entre {min_value} e {max_value} caracteres." )
  {
    if( $value >= $min_value and $value <= $max_value )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field, "min_value={$min_value}&max_value={max_value}" );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $length - description
   * @param   String $error - description
   * @return  mixed
   */
  function length( $value, $field, $length, $error = "O campo {field} deve ter {length} caracteres." )
  {
    if( mb_strlen( $value, 'UTF-8' ) >= $length )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field, "length={$length}" );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-10-31
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $min_length - description
   * @param   String $error - description
   * @return  mixed
   */
  function min_length( $value, $field, $min_length, $error = "O campo {field} deve ter no mínimo {min_length} caracteres." )
  {
    if( mb_strlen( $value, 'UTF-8' ) >= $min_length )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field, "min_length={$min_length}" );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-10-31
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $max_length - description
   * @param   String $error - description
   * @return  mixed
   */
  function max_length( $value, $field, $max_length, $error = "O campo {field} deve ter no máximo {max_length} caracteres." )
  {
    if( mb_strlen( $value, 'UTF-8' ) <= $max_length )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field, "max_length={$max_length}" );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-10-31
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $min_length - description
   * @param   String $max_length - description
   * @param   String $error - description
   * @return  mixed
   */
  function between_length( $value, $field, $min_length, $max_length, $error = "O campo {field} deve ter entre {min_length} e {max_length} caracteres." )
  {
    if( mb_strlen( $value, 'UTF-8' ) >= $min_length and mb_strlen( $value, 'UTF-8' ) <= $max_length )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field, "min_length={$min_length}&max_length={$max_length}" );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   String $error - description
   * @return  mixed
   */
  function email( $value, $field, $error = "O campo {field} deve conter um E-mail válido" )
  {
    if( preg_match( '/^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]+$/i', $value ) )
      return $value;
    elseif( $value and $error )
      $this->set_error( $error, $field );

    return false;
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   int $cpf - description
   * @param   String $field - description
   * @param   String $error - description
   * @return  mixed
   */
  function cpf( $cpf, $field, $error = "O campo {field} deve conter um CPF válido" )
  {
    if( !is_numeric( $cpf ) or $cpf == '00000000000' or $cpf == '11111111111' or $cpf == '22222222222' or $cpf == '33333333333' or $cpf == '44444444444' or $cpf == '55555555555' or $cpf == '66666666666' or $cpf == '77777777777' or $cpf == '88888888888' or $cpf == '99999999999' )
    {
      if( $error )
        $this->set_error( $error, $field );

      return false;
    }
    elseif( strlen( $cpf ) !== 11 )
    {
      if( $error )
        $this->set_error( $error, $field );

      return false;
    }
    else
    {
      $dvo = substr( $cpf, 9, 2 );

      for( $i = 0; $i < 9; $i++ )
        $digit[ $i ] = substr( $cpf, $i, 1 );

      // Primeiro Digito Verificador
      $sum = 0;
      $position = 10;

      for( $i = 0; $i < 9; $i++ )
      {
        $sum += $digit[ $i ] * $position;
        $position = $position - 1;
      }

      $digit[ 9 ] = $sum % 11;

      if( $digit[ 9 ] < 2 )
        $digit[ 9 ] = 0;
      else
        $digit[ 9 ] = 11 - $digit[ 9 ];

      // Segundo Digito Verificador
      $sum = 0;
      $position = 11;

      for( $i = 0; $i < 10; $i++ )
      {
        $sum += $digit[ $i ] * $position;
        $position = $position - 1;
      }

      $digit[ 10 ] = $sum % 11;

      if( $digit[ 10 ] < 2 )
        $digit[ 10 ] = 0;
      else
        $digit[ 10 ] = 11 - $digit[ 10 ];

      // Confere os Digitos Verificadores
      $dvv = $digit[ 9 ].$digit[ 10 ];

      if( $dvo == $dvv )
        return $cpf;
      elseif( $cpf and $error )
        $this->set_error( $error, $field );

      return false;
    }
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   int $cnpj - description
   * @param   String $field - description
   * @param   String $error - description
   * @return  mixed
   */
  function cnpj( $cnpj, $field, $error = "O campo {field} deve conter um CNPJ válido" )
  {
    if( !is_numeric( $cnpj ) or $cnpj == '00000000000000' or $cnpj == '11111111111111' or $cnpj == '22222222222222' or $cnpj == '33333333333333' or $cnpj == '44444444444444' or $cnpj == '55555555555555' or $cnpj == '66666666666666' or $cnpj == '77777777777777' or $cnpj == '88888888888888' or $cnpj == '99999999999999' )
    {
      if( $error )
        $this->set_error( $error, $field );

      return false;
    }
    elseif( strlen( $cnpj ) !== 14 )
    {
      if( $error )
        $this->set_error( $error, $field );

      return false;
    }
    else
    {
      $dvo = substr( $cnpj, 12, 2 );

      for( $i = 0; $i < 12; $i++ )
        $digit[ $i ] = substr( $cnpj, $i, 1 );

      // Primeiro Digito Verificador
      $sum = 0;
      $position = 5;

      for( $i = 0; $i < 12; $i++ )
      {
        $sum += $digit[ $i ] * $position;

        $position = ( $position == 2 ) ? $position = 9 : $position - 1;
      }

      $digit[ 12 ] = $sum % 11;

      if( $digit[ 12 ] < 2 )
        $digit[ 12 ] = 0;
      else
        $digit[ 12 ] = 11 - $digit[ 12 ];

      // Segundo Digito Verificador
      $sum = 0;
      $position = 6;

      for( $i = 0; $i < 13; $i++ )
      {
        $sum += $digit[ $i ] * $position;

        $position = ( $position == 2 ) ? $position = 9 : $position - 1;
      }

      $digit[ 13 ] = $sum % 11;

      if( $digit[ 13 ] < 2 )
        $digit[ 13 ] = 0;
      else
        $digit[ 13 ] = 11 - $digit[ 13 ];

      // Confere os Digitos Verificadores
      $dvv = $digit[ 12 ].$digit[ 13 ];

      if( $dvo == $dvv )
        return $cnpj;
      elseif( $cnpj and $error )
        $this->set_error( $error, $field );

      return false;
    }
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @return  mixed
   */
  function utf8( $value )
  {
    if( utf8_encode( utf8_decode( $value ) ) == $value )
      return $value;
    else
      return utf8_encode( $value );
  }

  /**
   * description
   *
   * @name
   * @author  Marcelo Mesquita <stallefish@gmail.com>
   * @since   2008-02-29
   * @updated 2011-08-01
   * @param   String $value - description
   * @param   String $field - description
   * @param   mixed $args - description
   * @return  mixed
   */
  function validate( $value, $field, $args, $error = '' )
  {
    $defaults = array(
      'required' => 0,
      'numeric' => 0,
      'alphabetic' => 0,
      'alphanumeric' => 0,
      'email' => 0,
      'cpf' => 0,
      'cnpj' => 0,
      'equal' => null,
      'equal_field' => null,
      'min_value' => null,
      'max_value' => null,
      'length' => null,
      'min_length' => null,
      'max_length' => null
    );

    if( is_array( $args ) )
      $parameters = $args;
    elseif( is_object( $args ) )
      $parameters = get_object_vars( $args );
    else
      parse_str( $args, $parameters );

    $parameters = array_merge( $defaults, $parameters ); // print_r( $parameters );

    extract( $parameters );

    // required
    if( $required )
      $value = $this->required( $value, $field );

    // numeric
    if( $numeric and $value )
      $value = $this->numeric( $value, $field );

    // alphabetic
    if( $alphabetic and $value )
      $value = $this->alphabetic( $value, $field );

    // alphanumeric
    if( $alphanumeric and $value )
      $value = $this->alphanumeric( $value, $field );

    // email
    if( $email and $value )
      $value = $this->email( $value, $field );

    // cpf
    if( $cpf and $value )
      $value = $this->cpf( $value, $field );

    // cnpj
    if( $cnpj and $value )
      $value = $this->cnpj( $value, $field );

    // equal
    if( $equal and $equal_field and $value )
      $value = $this->equal( $value, $field, $equal, $equal_field );

    // min value
    if( $min_value and $value )
      $value = $this->min_value( $value, $field, $min_value );

    // max value
    if( $max_value and $value )
      $value = $this->max_value( $value, $field, $max_value );

    // length
    if( $length and $value )
      $value = $this->length( $value, $field, $length );

    // min length
    if( $min_length and $value )
      $value = $this->min_length( $value, $field, $min_length );

    // max length
    if( $max_length and $value )
      $value = $this->max_length( $value, $field, $max_length );

    return $value;
  }

  // CONSTRUCTOR //////////////////////////////////////////////////////////////////////////////////
  function Validator()
  {
  }

  // DESTRUCTOR ///////////////////////////////////////////////////////////////////////////////////

}

$Validator = new Validator();

?>