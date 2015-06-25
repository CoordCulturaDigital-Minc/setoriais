<?php
/*
Plugin Name: Auto Thickbox
Plugin URI: http://www.semiologic.com/software/auto-thickbox/
Description: Automatically enables thickbox on thumbnail images (i.e. opens the images in a fancy pop-up).
Author: Denis de Bernardy
Version: 2.0.3
Author URI: http://www.getsemiologic.com
Text Domain: auto-thickbox
Domain Path: /lang
*/

/*
Terms of use
------------

This software is copyright Mesoconcepts (http://www.mesoconcepts.com), and is distributed under the terms of the GPL license, v.2.

http://www.opensource.org/licenses/gpl-2.0.php
**/


load_plugin_textdomain('auto-thickbox', false, dirname(plugin_basename(__FILE__)) . '/lang');


/**
 * auto_thickbox
 *
 * @package Auto Thickbox
 **/

class auto_thickbox {
	/**
	 * filter()
	 *
	 * @param array $anchor
	 * @return anchor $anchor
	 **/

	function filter($anchor) {
		if ( preg_match("/\.(?:jpe?g|gif|png)\b/i", $anchor['attr']['href']) )
			return auto_thickbox::image($anchor);
		elseif ( !empty($anchor['attr']['class']) && in_array('thickbox', $anchor['attr']['class']) )
			return auto_thickbox::iframe($anchor);
		else
			return $anchor;
	} # filter()
	
	
	/**
	 * image()
	 *
	 * @param array $anchor
	 * @return anchor $anchor
	 **/

	function image($anchor) {
		if ( !preg_match("/^\s*<\s*img\s.+?>\s*$/is", $anchor['body']) )
			return $anchor;
		
		if ( !$anchor['attr']['class'] ) {
			$anchor['attr']['class'][] = 'thickbox';
			$anchor['attr']['class'][] = 'no_icon';
		} else {
			if ( !in_array('thickbox', $anchor['attr']['class']) && !in_array('nothickbox', $anchor['attr']['class']) && !in_array('no_thickbox', $anchor['attr']['class']) )
				$anchor['attr']['class'][] = 'thickbox';
			if ( !in_array('no_icon', $anchor['attr']['class']) && !in_array('noicon', $anchor['attr']['class']) )
				$anchor['attr']['class'][] = 'no_icon';
		}
		
		if ( in_the_loop() && !$anchor['attr']['rel'] )
			$anchor['attr']['rel'][] = 'gallery-' . get_the_ID();
		
		if ( empty($anchor['attr']['title']) ) {
			if ( preg_match("/\b(?:alt|title)\s*=\s*('|\")(.*?)\\1/i", $anchor['body'], $title) ) {
				$anchor['attr']['title'] = end($title);
			}
		}
		
		return $anchor;
	} # image()
	
	
	/**
	 * iframe()
	 *
	 * @return void
	 **/
	
	function iframe($anchor) {
		if ( strpos($anchor['attr']['href'], 'TB_iframe=true') !== false )
			return $anchor;
		
		# strip anchor ref
		$href = explode('#', $anchor['attr']['href']);
		$anchor['attr']['href'] = array_shift($href);
		
		$anchor['attr']['href'] .= ( ( strpos($anchor['attr']['href'], '?') === false ) ? '?' : '&' )
			. 'TB_iframe=true&width=720&height=540';
		
		return $anchor;
	} # iframe()
	
	
	/**
	 * scripts()
	 *
	 * @return void
	 **/

	function scripts() {
		wp_enqueue_script('thickbox');
		wp_localize_script('thickbox', 'thickboxL10n', array(
			'next' => __('Next &gt;', 'auto-thickbox'),
			'prev' => __('&lt; Prev', 'auto-thickbox'),
			'image' => __('Image', 'auto-thickbox'),
			'of' => __('of', 'auto-thickbox'),
			'close' => __('Close', 'auto-thickbox'),
			'l10n_print_after' => 'try{convertEntities(thickboxL10n);}catch(e){};'
		));
	} # scripts()
	
	
	/**
	 * styles()
	 *
	 * @return void
	 **/

	function styles() {
		wp_enqueue_style('thickbox');
	} # styles()
	
	
	/**
	 * thickbox_images()
	 *
	 * @return void
	 **/

	function thickbox_images() {
		$includes_url = includes_url();
		
		echo <<<EOS

<script type="text/javascript">
var tb_pathToImage = "{$includes_url}js/thickbox/loadingAnimation.gif";
var tb_closeImage = "{$includes_url}js/thickbox/tb-close.png";
</script>

EOS;
	} # thickbox_images()
} # auto_thickbox

if ( !is_admin() && isset($_SERVER['HTTP_USER_AGENT']) &&
    	strpos($_SERVER['HTTP_USER_AGENT'], 'W3C_Validator') === false) {
	if ( !class_exists('anchor_utils') )
		include dirname(__FILE__) . '/anchor-utils/anchor-utils.php';
		
	add_action('wp_print_scripts', array('auto_thickbox', 'scripts'));
	add_action('wp_print_styles', array('auto_thickbox', 'styles'));
	
	add_action('wp_footer', array('auto_thickbox', 'thickbox_images'), 20);
	
	add_filter('filter_anchor', array('auto_thickbox', 'filter'));
}
?>