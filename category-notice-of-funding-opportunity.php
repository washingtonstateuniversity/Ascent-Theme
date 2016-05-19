<?php get_header(); ?>

<main class="spine-page-default">
	<?php get_template_part('parts/headers'); ?>
	<header class="main-header">
				<h1><span class="ascent-main-header">Ascent</span> Funding Opportunities</h1>
			</header>
	<?php get_template_part('parts/featured-images'); ?>


	<section class="row side-right gutter pad-ends">
		<div class="column one">
		<header>
			<h1>Notice of Funding Opportunity</h1>
			<?php echo category_description(); ?>
		</header>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'articles/post-notice-of-funding-opportunity', get_post_type() ); ?>
			<?php endwhile; // end of the loop. ?>

		</div><!--/column-->
	</section>
</main>

<?php get_footer();
