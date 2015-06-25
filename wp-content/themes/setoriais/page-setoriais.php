<?php get_header(); ?>

<div id="body" class="container">
	<div id="content" class="aside">
		<div class="section section-pile">
			<div class="section-body">
				<?php $blogs = get_blog_list( 2, 17 ); ?>
				<?php foreach( $blogs as $blog ) : ?>
					<?php if( function_exists( 'switch_to_blog' ) ) switch_to_blog( $blog[ 'blog_id' ] ); ?>
						<?php $blog_title = get_bloginfo( 'title' ); ?>
						<?php $blog_title = str_replace( 'Fórum Nacional Setorial ', '', $blog_title ); ?>

						<div class="post">
							<h1 class="post-title"><a href="<?php bloginfo( 'url' ); ?>" title="<?php bloginfo( 'title' ); ?>"><?php print $blog_title; ?></a></h1>
							<div class="post-entry"><?php bloginfo( 'description' ); ?></div>
							<?php
							/*switch ($blog[ 'blog_id' ])
							{
								case "2":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/2";
									break;
								case "3":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/3";
									break;
								case "4":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/4";
									break;
								case "5":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/5";
									break;
								case "6":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/6";
									break;
								case "7":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/7";
									break;
								case "8":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/8";
									break;
								case "9":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/9";
									break;
								case "10":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/10";
									break;
								case "11":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/11";
									break;
								case "12":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/12";
									break;
								case "13":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/13";
									break;
								case "14":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/14";
									break;
								case "15":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/15";
									break;
								case "16":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/16";
									break;
								case "17":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/17";
									break;
								case "18":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/18";
									break;
								case "19":
									$link = "http://cnpc.cultura.gov.br/inscricao/form-inscricao/setorial/19";
									break;
									
							}*/
							?>
							<a href="<?php print $link ; ?>" title="Participe" class="more-link">Participe &rsaquo;</a>							
						</div>
					<?php if( function_exists( 'restore_current_blog' ) ) restore_current_blog(); ?>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</div>

<?php get_footer(); ?>