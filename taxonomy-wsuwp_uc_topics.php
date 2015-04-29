<?php get_header(); ?>

<main class="spine-page-default">
	<?php get_template_part('parts/headers'); ?>
	<?php get_template_part('parts/featured-images'); ?>

	<section class="row side-right gutter pad-ends">
		<div class="column one">
			<header>
				<h1><?php echo esc_html( single_term_title( '', false ) ); ?></h1>
			</header>
			<p><?php echo wp_kses_post( term_description() ); ?></p>

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'articles/post', get_post_type() ); ?>
			<?php endwhile; // end of the loop. ?>

		</div><!--/column-->
	</section>
</main>

<?php get_footer();