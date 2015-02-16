<?php get_header(); ?>

<main class="spine-page-default">

<?php get_template_part('parts/headers'); ?>
<?php get_template_part('parts/featured-images'); ?>

<section class="row side-right gutter pad-ends">

	<div class="column one">
<?php
if (is_tax( 'wsuwp_uc_topics', 'alternative-fuels' )) {
  echo "<header>
  <h1>Alternative Fuels</h1>
  </header>";
} else if (is_tax( 'wsuwp_uc_topics', 'noise' )) {
 echo "<header>
  <h1>Noise</h1>
  </header>";
}
else if (is_tax( 'wsuwp_uc_topics', 'emissionsclimate' )) {
 echo "<header>
  <h1>Emissions/Climate</h1>
  </header>";
}
else if (is_tax( 'wsuwp_uc_topics', 'design-and-operations' )) {
 echo "<header>
  <h1>Design and Operations</h1>
  </header>";
}
?>
	<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'articles/post', get_post_type() ); ?>

		<?php endwhile; // end of the loop. ?>

	</div><!--/column-->

</section>

</main>

<?php get_footer(); ?>