<?php if ( !comments_open() ) return false; ?>

<?php global $user_email; ?>

<div class="section section-comment">
	<div class="section-head">
		<h1 class="section-title">Comentários (<?php comments_number( '0', '1', '%' ); ?>)</h1>
	</div>
	<div class="section-body">
		<?php if ( have_comments() ) : ?>
			<ul>
				<?php wp_list_comments( array( 'callback' => 'enhanced_comments' ) ); ?>
			</ul>
		<?php endif; ?>

		<?php if( function_exists( 'previous_comments_link' ) and function_exists( 'next_comments_link' ) ) : ?>
			<div class="section-foot">
				<div class="pagination">
					<?php next_comments_link( '&laquo;' ); ?>
					<?php previous_comments_link( '&raquo;' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<h2 class="section-subtitle">Participe!</h2>
		<?php if( get_option( 'comment_registration' ) and !is_user_logged_in() ) : ?>
			<li id="comment-<?php comment_ID(); ?>" class="comment">
				<?php print get_avatar( null, '60' ); ?>
				<div class="balloon">
					<div class="balloon-tip"></div>
					<div class="comment-author"><a href="<?php print wp_login_url( SITE_URL ); ?>" title="Login">Login</a></div>
					<div class="comment-entry entry">
						<p>Você precisa estar logado para fazer um comentário!</p>
					</div>
				</div>
				<div class="clear"></div>
			</li>
		<?php else : ?>
			<form action="<?php print site_url( '/wp-comments-post.php' ); ?>" method="post" id="respond" class="comment">
				<?php if( function_exists( 'comment_id_fields' ) ) comment_id_fields(); ?>
				<?php print get_avatar( $user_email, '60' ); ?>
				<div class="balloon">
					<div class="balloon-tip"></div>
					<label for="comment" class="invisible">Comentário</label>
					<textarea id="comment" name="comment"></textarea>
				</div>
				<div class="clear"></div>
				<?php do_action( 'comment_form', $post->ID ); ?>
				<div class="clear"></div>
				<button type="submit" name="submit">Enviar</button>
				<?php cancel_comment_reply_link( 'Cancelar' ); ?>
				<div class="clear"></div>
			</form>
		<?php endif; ?>
	</div>
</div>