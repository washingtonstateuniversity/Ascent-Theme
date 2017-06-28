<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="article-header">
		<hgroup>
			<div class="ascent-num-box">
				<h3 class="ascent-num-title">ASCENT No.</h3>
				<h2 class="ascent-num"><?php echo esc_html( ascent_get_project_number() );?></h2>
			</div>
			<?php if ( is_single() ) : ?>
				<h1 class="article-title"><?php the_title(); ?></h1>
			<?php else : ?>
				<h2 class="article-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php endif; ?>
		</hgroup>
	</header>
	<?php if ( ! is_singular() ) : ?>
		<div class="article-summary">
			<?php

			if ( has_post_thumbnail() ) {
				?><figure class="article-thumbnail"><?php the_post_thumbnail( array( 132, 132, true ) ); ?></figure><?php
			}

			// If a manual excerpt is available, display this. Otherwise, only the most basic information is needed.
			if ( $post->post_excerpt ) {
				echo get_the_excerpt();
			}

			?>
		</div><!-- .article-summary -->
	<?php else : ?>
		<div class="article-body">
			<?php the_content(); ?>

			<?php // Display the date the project was last updated.
			$revisions = wp_get_post_revisions( get_the_ID(), array(
				'numberposts' => 1,
			) );

			// Calculate the last updated display based on our current timezone.
			$current_time = time() + ( get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS );

			foreach ( $revisions as $revision ) {
				echo '<p class="last-updated"><span class="last-updated-text">Last Updated:</span> <span class="last-updated-date">';

				// If within 24 hours, show a human readable version instead
				if ( ( $current_time - strtotime( $revision->post_date ) ) < DAY_IN_SECONDS ) {
					echo esc_html( human_time_diff( $current_time, strtotime( $revision->post_date ) ) . ' ago' );
				} else {
					echo esc_html( date( 'm/d/Y', strtotime( $revision->post_date ) ) );
				}

				echo '</span></p>';
			}
			?>

			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'spine' ), 'after' => '</div>' ) ); ?>
		</div>
	<?php endif; ?>
</article>
