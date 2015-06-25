<?php global $theme_options; ?>

<?php if( !empty( $theme_options[ 'cycle' ] ) ) : ?>
	<?php $cycle = new WP_Query( "cat={$theme_options[ 'cycle' ]}" ); ?>
	<?php if( $cycle->have_posts() ) : ?>
		<div class="section section-cycle">
			<div class="section-body">
				<?php while( $cycle->have_posts() ) : $cycle->the_post(); ?>
					<div class="post">
						<div class="post-head">
							<h1 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php print limit_chars( get_the_title(), 50 ); ?></a></h1>
							<div class="post-entry"><?php print limit_chars( get_the_excerpt(), 100 ); ?></div>
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="more-link">Leia mais</a>
						</div>
						<?php the_post_thumbnail( 'cycle' ); ?>
					</div>
				<?php endwhile; ?>
			</div>
			<div class="section-foot"><div class="pagination"></div></div>
		</div>
	<?php endif; ?>
<?php endif; ?>