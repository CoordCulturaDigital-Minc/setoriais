<?php get_header(); ?>

<?php $useds = array(); ?>

<div id="body">
	<div id="content">
		<?php if( have_posts() ) : ?>
			<?php if( is_home() ) : ?>
				<?php if( !empty( $theme_options[ 'cycle' ] ) ) : ?>
					<?php $cycle = new WP_Query( "cat={$theme_options[ 'cycle' ]}&showposts=5" ); ?>
					<?php if( $cycle->have_posts() ) : ?>
						<div class="section section-cycle">
							<div class="section-body">
								<?php while( $cycle->have_posts() ) : $cycle->the_post(); ?>
									<?php $useds[] = get_the_ID(); ?>
									<div class="post">
										<div class="post-head <?php if( !has_post_thumbnail() ) print 'odd'; ?>">
											<h1 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
											<div class="post-entry"><?php print limit_chars( get_the_excerpt(), ( has_post_thumbnail() ) ? 200 : 400 ); ?></div>
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
			<?php endif; ?>

			<div class="section section-index">
				<div class="section-head">
					<?php if( is_home() ) : ?>
					<?php elseif( is_category() ) : ?>
						<h1 class="section-title"><?php single_cat_title(); ?></h1>
					<?php elseif( is_tag() ) : ?>
						<h1 class="section-title"><?php single_tag_title(); ?></h1>
					<?php elseif( is_day() ) : ?>
						<h1 class="section-title">Posts do dia <span><?php print get_the_time( 'd \d\e F \d\e Y' ); ?><span></h1>
					<?php elseif( is_month() ) : ?>
						<h1 class="section-title">Posts do mês <span><?php print get_the_time( 'F \d\e Y' ); ?></span></h1>
					<?php elseif( is_year() ) : ?>
						<h1 class="section-title">Posts do ano <span><?php print get_the_time( 'Y' ); ?></span></h1>
					<?php elseif( is_author() ) : ?>
						<h1 class="section-title">Posts do autor <span><?php print get_userdata( intval( $author ) )->user_nicename; ?></span></h1>
					<?php elseif( is_search() ) : ?>
						<h1 class="section-title">Resultados para <span>&quot;<?php the_search_query(); ?>&quot;</span></h1>
					<?php endif; ?>
				</div>

				<div class="section-body">
					<?php parse_str( $query_string, $query_array ); ?>
					<?php $query_array[ 'post__not_in' ] = $useds; ?>
					<?php query_posts( $query_array ); ?>
					<?php while( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'loop' ); ?>
					<?php endwhile; ?>
				</div>

				<div class="section-foot">
					<div class="pagination alignright">
						<?php if( function_exists( 'wp_pagenavi' ) ) : ?>
							<?php wp_pagenavi(); ?>
						<?php else : ?>
							<?php next_posts_link( '&laquo;' ); ?>
							<?php previous_posts_link( '&raquo;'); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php else : ?>
			<?php get_template_part( 'error' ); ?>
		<?php endif; ?>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>