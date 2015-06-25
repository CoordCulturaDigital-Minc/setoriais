<?php
	// constants
	define( 'SITE_URL',        get_bloginfo( 'url' ) );
	define( 'SITE_TITLE',      get_bloginfo( 'name' ) );
	define( 'RSS_URL',         get_bloginfo( 'rss2_url' ) );
	define( 'THEME_URL',       get_bloginfo( 'template_directory' ) );
	define( 'CHILD_THEME_URL', get_bloginfo( 'stylesheet_directory' ) );

	// theme options
	$theme_options = get_option( 'theme_options' );

	// menus
	add_theme_support( 'menus' );

		// define menu location
		register_nav_menu( 'header', 'Header' );

	// thumbnails
	add_theme_support( 'post-thumbnails' );

		// define thumbnail size
		add_image_size( 'cycle', 300, 250, true );

	// sidebar structure
	function register_theme_sidebar( $name )
	{
		register_sidebar( array(
			'name'          => $name,
			'id'            => 'sidebar-1',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_head'   => '<div class="widget-head">',
			'after_head'    => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
			'before_body'   => '<div class="widget-body">',
			'after_body'    => '</div>',
			'before_foot'   => '<div class="widget-foot">',
			'after_foot'    => '</div>',
		) );
	}

	// sidebar
	register_theme_sidebar( 'Index' );

	// includes
	include( TEMPLATEPATH . '/inc/custom-theme.php' );
	include( TEMPLATEPATH . '/inc/post-count.php' );
	include( TEMPLATEPATH . '/inc/limit-chars.php' );
	include( TEMPLATEPATH . '/inc/enhanced-comments.php' );
	include( TEMPLATEPATH . '/inc/unregister-widgets.php' );

	// widgets
	include( TEMPLATEPATH . '/widgets/sav-widget-text.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-login.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-search.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-tag-cloud.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-contador.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-contador-uf.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-candidatos.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-custom-menu.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-custom-loop.php' );
	include( TEMPLATEPATH . '/widgets/sav-widget-custom-page.php' );

	// specific theme functions
	if( file_exists( STYLESHEETPATH . '/functions-child.php' ) )
		include( STYLESHEETPATH . '/functions-child.php' );
?>