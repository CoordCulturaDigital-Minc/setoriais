<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name="Description" content="<?php bloginfo( 'description' ); ?>" />
		<meta name="Keywords" content="" />
		<meta name="Copyright" CONTENT="&copy; 2012 Ministério da Cultura do Brasil" />
		<meta name="Author" content="Marcelo Mesquita, Jaqueline Teles" />

		<title><?php print SITE_TITLE; ?> <?php wp_title( '&raquo;', true, 'left' ); ?></title>

		<link type="image/x-icon" href="<?php print THEME_URL; ?>/favicon.ico" rel="shortcut icon" />

		<?php wp_enqueue_style( 'economica', 'http://fonts.googleapis.com/css?family=Economica:400,700' ); ?>

		<?php wp_enqueue_style( 'theme', THEME_URL . '/css/style.css', array(), false, 'screen' ); ?>
		<?php wp_enqueue_style( 'print', THEME_URL . '/css/print.css', array(), false, 'print' ); ?>

		<?php wp_enqueue_script( 'jquery' ); ?>
		<?php wp_enqueue_script( 'jcycle', THEME_URL . '/js/jcycle-2.3.min.js', array( 'jquery' ) ); ?>
		<?php wp_enqueue_script( 'jfontsize', THEME_URL . '/js/jfontsize-1.0.min.js', array( 'jquery' ) ); ?>
		<?php wp_enqueue_script( 'script', THEME_URL . '/js/script.js', array( 'jquery' ) ); ?>

		<?php wp_enqueue_script( 'comment-reply' ); ?>

		<?php wp_head(); ?>
	</head>

	<body>

        <?php
        $_tamanhoLayout   = '980';
        $_margemTopLayout = '484';
        $_margemMenu      = '25';
        ?>
	<?php //include( '/var/www/menu.php' ); ?>

		<div class="container">
			<div id="topo-setorial">
                            <a href="http://www.cultura.gov.br" title="www.cultura.gov.br" style="display:block; float:left; width:200px; height:85px;"></a>
                            <a href="http://www.cultura.gov.br/setoriais" title="www.cultura.gov.br/setoriais" style="display:block; float:right; width:700px; height:85px;"></a>
			</div>
                    <div id="header" style="height: 100px;">
                                <div id="accessibility">
					<span>Tamanho da Fonte</span>
					<a href="#decrease-font" class="decrease-font" title="Diminuir Fonte">A-</a>
					<a href="#increase-font" class="increase-font" title="Aumentar Fonte">A+</a>
				</div>
                           
                            <div id="search" style="float: right;">
					<form action="<?php print SITE_URL; ?>" method="get">
						<label for="s" class="invisible">procurar</label>
						<input type="text" name="s" value="digite aqui o que procura..." class="memory" />
						<button type="submit">IR</button>
					</form>
				</div>
                            
                                <style>
                                    #menu-item-157{
                                        padding-left: 0px !important;
                                    }
                                </style>
                                <div id="navigator" style="float: left; width: 560px; position: relative; bottom: 0px; margin-top: 60px;">
					<?php wp_nav_menu( 'theme_location=header' ); ?>
				</div>


			</div>
			<?php if( function_exists( 'yoast_breadcrumb' ) ) : ?>
				<div id="breadcrumb" class="container">
					<p><?php yoast_breadcrumb(); ?></p>
				</div>
			<?php endif; ?>