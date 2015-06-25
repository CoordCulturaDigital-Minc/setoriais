<?php get_header(); ?>

<?php

global $CNPC, $withcomments;

$withcomments = true;

$candidato = get_userdata( ( int ) $author );

// load data
$dados_pessoais      = $CNPC->get_dados_pessoais( $candidato->user_login );
$dados_geograficos   = $CNPC->get_dados_geograficos( $candidato->user_login );
$dados_profissionais = $CNPC->get_dados_profissionais( $candidato->user_login );
$dados_candidatura   = $CNPC->get_dados_candidatura( $candidato->user_login );

?>

<div id="body">
	<div id="content">
		<div class="section section-author">
			<div class="section-head">
				<?php print get_avatar( $candidato->user_email, '60' ); ?>
				<h1 class="section-title"><?php print $candidato->display_name; ?></h1>
				<?php $CNPC->vote_button( $candidato->ID ); ?><br><br><br>
				<h2 class="section-subtitle"><?php print $dados_profissionais[ 'atuacao' ]; ?> de <?php print $dados_geograficos[ 'cidade' ]; ?> - <?php print $dados_geograficos[ 'estado' ]; ?></h2>

				<div class="clear"></div>
			</div>
		</div>

		<?php query_posts( "name={$candidato->user_nicename}" ); ?>
		<?php if( have_posts() ) : the_post(); ?>
			<div class="section section-post">
				<div class="section-body">
					<div id="post-<?php the_ID(); ?>" class="post">
						<h1 class="post-title"><span><?php the_title(); ?><span></h1>

						<div class="post-entry entry"><?php the_content(); ?></div>

						<?php edit_post_link( 'editar post &raquo;', '<div class="post-meta">', '</div>' ); ?>
						<?php if( $theme_options[ 'post_author' ] ) : ?><div class="post-meta">Autor: <?php the_author_posts_link() ?></a></div><?php endif; ?>
						<?php if( $theme_options[ 'post_date' ] ) : ?><div class="post-meta">Publicado em: <a href="<?php print get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ); ?>" title="<?php the_time( 'j \d\e F \d\e Y' ); ?>"><time datetime="<?php the_time( 'Y-m-d\TH:i:s+00:00' ); ?>"><?php the_time( 'j \d\e F \d\e Y' ); ?></time></a></div><?php endif; ?>
						<?php if( $theme_options[ 'post_modified_date' ] ) : ?><div class="post-meta">Atualizado em <a href="<?php print get_day_link( get_the_modified_time( 'Y' ), get_the_modified_time( 'm' ), get_the_modified_time( 'd' ) ); ?>" title="<?php the_modified_time(); ?>"><?php the_modified_time(); ?></a></div><?php endif; ?>
						<?php if( $theme_options[ 'post_tag' ] ) : ?><div class="post-meta">Tags: <?php the_tags( ' ', ', ' ); ?></div><?php endif; ?>
						<?php if( $theme_options[ 'post_category' ] ) : ?><div class="post-meta">Categoria: <?php the_category( ', ' ); ?></div><?php endif; ?>
						<?php if( $theme_options[ 'post_comments' ] ) : ?><div class="post-meta">Comentários: <?php comments_popup_link( '0', '1', '%' ); ?></div><?php endif; ?>
					</div>
				</div>
			</div>

			<?php comments_template(); ?>
		<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>