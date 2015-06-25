<?php get_header(); ?>

<div id="body" class="container">
	<div id="content">
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
		<?php else : ?>
			<?php get_template_part( 'error' ); ?>
		<?php endif; ?>

		<?php comments_template(); ?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>