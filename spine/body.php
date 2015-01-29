<?php

if ( true == spine_get_option( 'crop' ) && is_front_page() ) {
	$cropping = ' cropped';
} else {
	$cropping = '';
}

?>

<div id="spine" class="spine-column asc-heavy-gray <?php echo esc_attr( spine_get_option( 'bleed' ) ); ?> shelved">
<div id="glue" class="spine-glue">
<?php get_template_part( 'spine/header' ); ?>

	<section id="spine-navigation" class="spine-navigation">

		<?php get_template_part( 'spine/site-navigation' ); ?>

	</section>

	<section id="side-latest-news">
		<h4>Latest news item</h4>
		<div class="side-latest-news-box">
		<?php
				$args = array(
				'posts_per_page' => 1,
				'post_type' => 'post',
				'tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field' => 'slug',
						'terms' => 'side-bar'
					),
				),
			);
			$my_posts = new WP_Query( $args );
			if ( $my_posts->have_posts() ) : while( $my_posts->have_posts() ) : $my_posts->the_post();
				?>
				<h5 class="blog-side-title"><?php echo get_the_title(); ?></h5>
				<span class="blog-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 16, '...' ); ?></span>
				<span class="rmore"><a href="<?php the_permalink(); ?>">More</a></span>
			<?php endwhile; endif;
			wp_reset_query();
			?>

</div>

	</section>

<?php get_template_part( 'spine/footer' ); ?>

</div><!--/glue-->
</div><!--/spine-->