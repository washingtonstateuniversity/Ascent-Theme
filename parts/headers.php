<?php
if ( is_post_type_archive( 'wsuwp_uc_entity' ) ) {
	$header_headline = 'University Partners';
} elseif ( is_post_type_archive( 'wsuwp_uc_person' ) ) {
	$header_headline = 'Members';
} elseif ( is_post_type_archive( 'wsuwp_uc_project' ) || is_singular( 'wsuwp_uc_project' ) ) {
	$header_headline = 'Projects';
} elseif ( is_tax( 'wsuwp_uc_entity_type' ) ) {
	$header_headline = single_term_title( '', false );
} elseif ( is_tax( 'wsuwp_uc_topics' ) ) {
	$header_headline = 'Topics';
} elseif ( is_singular( 'wsuwp_uc_entity' ) ) {
	$header_headline = 'Partners';
}

?>
<header class="main-header">
	<h1><span class="ascent-main-header">Ascent</span> <?php echo $header_headline; ?></h1>
</header>