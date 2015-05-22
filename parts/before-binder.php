
<?php if( is_front_page() ) { ?>
	<div id="videobg" class="videobg">
		<div class="banner-container">
			<div class="logo-hp-container">
			
			</div>
		</div>
	</div>
	<script type='text/javascript'>
		/* <![CDATA[ */
		var wsu_video_background = <?php echo swwrc_get_video_json_data(); ?>;
		/* ]]> */
	</script>
<?php } ?>
<a class="mobilenav" href="#"><div id="mobilelogo"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/ascent-logo-wm.png' ); ?>" ></div>
</a>
<nav class="main-menu navreg">
	<div id="logo">
		<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/ascent-logo-wm.png' ); ?>" ></a>
</div>
<?php
	$spine_site_args = array(
		'theme_location'  => 'site',
		'menu'            => 'site',
		'container'       => false,
		'container_class' => false,
		'container_id'    => false,
		'menu_class'      => null,
		'menu_id'         => null,
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 3,
	);
	wp_nav_menu( $spine_site_args ); ?>
</nav>