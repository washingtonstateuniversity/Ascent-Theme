<section class="row single gutter pad-ends">
	<div class="column one">
		<header>
				<h1 class="article-title"><?php the_title(); ?></h1>
		</header>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'articles/post', get_post_type() ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!--/column-->

</section>