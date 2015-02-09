<section class="row single gutter pad-ends">
	<div class="column one">
<?php
if (is_post_type_archive( 'wsuwp_uc_entity' )) {
  echo "<header>
  <h1>University Partners</h1>
  </header>";
} else if ( is_post_type_archive( 'wsuwp_uc_person' )) {
 echo "<header>
  <h1>Advisory Committee</h1>
  </header>";
}
else if ( is_post_type_archive( 'wsuwp_uc_project' )) {
 echo "<header>
  <h1>Projects</h1>
  </header>";
}
else if ( is_post_type_archive( 'wsuwp_uc_topic' )) {
 echo "<header>
  <h1>Topics</h1>
  </header>";
}
?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'articles/post', get_post_type() ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!--/column-->

</section>