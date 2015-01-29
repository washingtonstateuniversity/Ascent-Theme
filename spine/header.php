<header class="spine-header">
	<a href="/" id="wsu-signature">Ascent</a>
</header>
<section id="side-search-area" class="clearfix">
<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<label class="screen-reader-text" for="s"><?php _x( 'Search for:', 'label' ); ?></label>
		<input type="text" value="Search <?php echo get_search_query(); ?>" name="s" id="s" onblur="if(this.value == '') { this.value='Search <?php echo get_search_query(); ?>'}" onfocus="if (this.value == 'Search <?php echo get_search_query(); ?>') {this.value=''}"/>
		<!--input type="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />-->
	</div>
</form>

</section><!--/#wsu-actions-->