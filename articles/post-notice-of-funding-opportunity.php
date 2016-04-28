<?php
$post_share_url = esc_url( get_permalink() );
$post_share_title = rawurlencode( spine_get_title() );
$post_share_placement = spine_get_option( 'post_social_placement' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="article-header">

		<?php if ( is_single() ) : ?>
			<?php if ( true === spine_get_option( 'articletitle_show' ) ) : ?>
				<h1 class="article-title"><?php the_title(); ?></h1>
			<?php endif; ?>
		<?php else : ?>
			<dl  class="ascent-num">
				<dt> <h2 class="article-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h2></dt>
			</dl>
		<?php endif; // is_single() or in_a_relationship() ?>
		</hgroup>

		<?php if ( is_singular() && in_array( $post_share_placement, array( 'top', 'both' ) ) ) : ?>
		
		<?php endif; ?>
	</header>

	<?php if ( ! is_singular() ) : ?>
		
	<?php else : ?>
		<div class="article-body">

			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'spine' ), 'after' => '</div>' ) ); ?>
		</div>
	<?php endif; ?>
</article>
