<section class="row single gutter pad-ends">
	<div class="column one">
<?php
if ( is_post_type_archive('wsuwp_uc_entity') ) {
    ?>
    <header>
    	<h1>University Partners</h1>
	</header>
    <?php
}
?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'articles/post', get_post_type() ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!--/column-->

</section>