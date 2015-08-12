<figure class="featured-image"></figure>
<?php

if ( is_post_type_archive( 'wsuwp_uc_entity' ) ) {
	$archive_headline = 'Universities';
} elseif ( is_post_type_archive( 'wsuwp_uc_person' ) ) {
	$archive_headline = 'Members';
} elseif ( is_post_type_archive( 'wsuwp_uc_project' ) ) {
	$archive_headline = 'Projects';
} elseif ( is_tax( 'wsuwp_uc_entity_type' ) ) {
	$archive_headline = single_term_title( '', false );
} else {
	$archive_headline = '';
}
?>
<section class="row single gutter pad-ends entity-section">
	<div class="column one">
		<header>
			<h1><?php echo $archive_headline; ?></h1>
		</header>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'articles/post', get_post_type() ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!--/column-->

</section>