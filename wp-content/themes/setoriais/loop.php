<?php global $theme_options; ?>

<div id="post-<?php the_ID(); ?>" class="post">
	<?php if( $theme_options[ 'index_thumb' ] ) : ?><?php the_post_thumbnail( 'thumb', array( 'class' => 'alignleft' ) ); ?><?php endif; ?>
	<?php if( $theme_options[ 'index_category' ] ) : ?><div class="post-category"><?php the_category( ', ' ); ?></div><?php endif; ?>
	<h1 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
	<?php if( $theme_options[ 'index_date' ] ) : ?><div class="post-date">publicado em <a href="<?php print get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ); ?>" title="<?php the_time( 'j \d\e F \d\e Y' ); ?>"><time datetime="<?php the_time( 'Y-m-d\TH:i:s+00:00' ); ?>"><?php the_time( 'j \d\e F \d\e Y' ); ?></time></a></div><?php endif; ?>
	<?php if( $theme_options[ 'index_modified_date' ] ) : ?><div class="post-date">atualizado em <a href="<?php print get_day_link( get_the_modified_time( 'Y' ), get_the_modified_time( 'm' ), get_the_modified_time( 'd' ) ); ?>" title="<?php the_modified_time(); ?>"><time datetime="<?php the_modified_time( 'Y-m-d\TH:i:s+00:00' ); ?>"><?php the_modified_time(); ?></time></a></div><?php endif; ?>
	<?php if( $theme_options[ 'index_excerpt' ] ) : ?><div class="post-entry"><?php the_excerpt(); ?></div><?php endif; ?>
	<?php if( $theme_options[ 'index_tag' ] ) : ?><div class="post-tag"><?php the_tags( ' ', ', ' ); ?></div><?php endif; ?>
	<?php if( $theme_options[ 'index_author' ] ) : ?><div class="post-author">por <?php the_author_posts_link(); ?></div><?php endif; ?>
	<?php if( $theme_options[ 'index_comments' ] ) : ?><div class="post-comment"><?php comments_popup_link( '0', '1', '%' ); ?></div><?php endif; ?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="more-link">Leia mais &gt;</a>
</div>
