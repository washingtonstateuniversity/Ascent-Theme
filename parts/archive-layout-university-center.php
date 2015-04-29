<section class="row single gutter pad-ends">
	<div class="column one">
<?php

if ( is_post_type_archive( 'wsuwp_uc_entity' ) ) {
	$archive_headline = 'University Partners';
} elseif ( is_post_type_archive( 'wsuwp_uc_person' ) ) {
	$archive_headline = 'Members';
} elseif ( is_post_type_archive( 'wsuwp_uc_project' ) ) {
	$archive_headline = 'Projects';
} elseif ( is_tax( 'wsuwp_uc_entity_type', 'university-partners' ) ) {
	$archive_headline = 'University Partners';
} elseif ( is_tax( 'wsuwp_uc_entity_type', 'advisory-committee' ) ) {
	$archive_headline = 'Advisory Committee';
}

?>
<header>
	<h1><?php echo $archive_headline; ?></h1>
</header>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'articles/post', get_post_type() ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!--/column-->

</section>