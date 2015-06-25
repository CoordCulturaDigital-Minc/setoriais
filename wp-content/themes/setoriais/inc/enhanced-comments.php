<?php
/**
 * Copyright (c) 2012 Marcelo Mesquita
 *
 * Written by Marcelo Mesquita <stallefish@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 *
 * Public License can be found at http://www.gnu.org/copyleft/gpl.html
 *
 * Function Name: Enhanced Comments
 * Function URI: http://marcelomesquita.com/
 * Description: List the comments with replies
 * Author: Marcelo Mesquita
 * Author URI: http://marcelomesquita.com/
 * Version: 0.1
 */

function enhanced_comments( $comment, $args, $depth )
{
	$GLOBALS[ 'comment' ] = $comment;

	?>
		<?php if( $depth > 1 ) : ?><div class="reply-tip"></div><?php endif; ?>
		<li id="comment-<?php comment_ID(); ?>" class="comment">
			<?php print get_avatar( $comment, '60' ); ?>
			<div class="balloon">
				<div class="balloon-tip"></div>
				<?php comment_reply_link( array( 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ) ); ?>
				<div class="comment-author"><?php comment_author_link(); ?></div>
				<div class="comment-meta"><?php print human_time_diff( strtotime( get_comment_time( 'Y-m-d H:i' ) ) ); ?> atrás</div>
				<div class="comment-entry entry">
					<?php if( '0' == $comment->comment_approved ) : ?>
						<p class="comment-wait">Seu comentário está aguardando moderação!</p>
					<?php endif; ?>
					<?php comment_text(); ?>
				</div>
			</div>
			<div class="clear"></div>
		</li>
	<?php
}

?>
