<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php if ( ! is_singular() ) : ?>
	<?php

			if ( has_post_thumbnail() ) {
				?><figure class="article-thumbnail"><?php the_post_thumbnail( array( 132, 132, true ) ); ?></figure><?php
			}

			// If a manual excerpt is available, display this. Otherwise, only the most basic information is needed.
			if ( $post->post_excerpt ) {
				echo get_the_excerpt();
			}

			?>
		<div class="article-summary">
			
		</div><!-- .article-summary -->
	<?php if ( is_single() ) : ?>
		<h1><?php the_title(); ?></h1>
	<?php else : ?>
		<p><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></p>
	<?php endif; ?>

	
	<?php else : ?>
		<div class="article-body">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'spine' ), 'after' => '</div>' ) ); ?>
		</div>
	<?php endif; ?>

</article>