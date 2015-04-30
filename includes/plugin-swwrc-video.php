<?php
class WSU_SWWRC_Video {
	/**
	 * @var string Meta key for storing headline.
	 */
	public $headline_meta_key = '_wsu_cob_headline';
	/**
	 * Setup the hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10 );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		add_shortcode( 'cob_home_headline', array( $this, 'display_home_headline' ) );
	}
	/**
	 * Add metaboxes for subtitle and call to action to page and post edit screens.
	 *
	 * @param string $post_type Current post type screen being displayed.
	 */
	public function add_meta_boxes( $post_type ) {
		if ( ! in_array( $post_type, array( 'page', 'post' ) ) ) {
			return;
		}
		add_meta_box( 'wsu_cob_headlines', 'Page Headlines', array( $this, 'display_headlines_metabox' ), null, 'normal', 'high' );

		$show_on_front = get_option( 'page_on_front', false );

		if ( $show_on_front == get_the_ID() ) {
			add_meta_box( 'wsu_swwrc_video', 'Home Page Videos', array( $this, 'display_videos_metabox' ), null, 'normal', 'default' );
		}
	}

	/**
	 * Display the metabox used to capture additional headlines for a post or page.
	 *
	 * @param WP_Post $post
	 */
	public function display_headlines_metabox( $post ) {
		$headline = get_post_meta( $post->ID, $this->headline_meta_key, true );
		wp_nonce_field( 'cob-headlines-nonce', '_cob_headlines_nonce' );
		?>
		<label for="cob-page-headline">Headline:</label>
		<input type="text" class="widefat" id="cob-page-headline" name="cob_page_headline" value="<?php echo esc_attr( $headline ); ?>" />
		<p class="description">Primary headline to be used for the top of the page, under the logo.</p>
	<?php
	}

	/**
	 * Display the metabox used to capture the videos and poster image associated
	 * with the site's video background.
	 *
	 * @param WP_Post $post The post object currently being edited.
	 */
	public function display_videos_metabox( $post ) {
		$mp4 = get_post_meta( $post->ID, '_swwrc_home_mp4', true );
		$ogv = get_post_meta( $post->ID, '_swwrc_home_ogv', true );
		$web = get_post_meta( $post->ID, '_swwrc_home_webm', true );
		$poster = get_post_meta( $post->ID, '_swwrc_home_poster', true );
		?>
		<label for="swwrc-home-mp4">MP4 URL:</label>
		<input type="text" class="widefat" id="swwrc-home-mp4" name="swwrc_home_mp4" value="<?php echo esc_attr( $mp4 ); ?>" />
		<label for="swwrc-home-ogv">OGV URL:</label>
		<input type="text" class="widefat" id="swwrc-home-ogv" name="swwrc_home_ogv" value="<?php echo esc_attr( $ogv ); ?>" />
		<label for="swwrc-home-web">WEBM URL:</label>
		<input type="text" class="widefat" id="swwrc-home-web" name="swwrc_home_web" value="<?php echo esc_attr( $web ); ?>" />
		<label for="swwrc-home-poster">Poster URL:</label>
		<input type="text" class="widefat" id="swwrc-home-poster" name="swwrc_home_poster" value="<?php echo esc_attr( $poster ); ?>" />
	<?php
	}

	/**
	 * Save the subtitle and call to action assigned to the post.
	 *
	 * @param int     $post_id ID of the post being saved.
	 * @param WP_Post $post    Post object of the post being saved.
	 */
	public function save_post( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! in_array( $post->post_type, array( 'page', 'post' ) ) ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( ! isset( $_POST['_cob_headlines_nonce'] ) || false === wp_verify_nonce( $_POST['_cob_headlines_nonce'], 'cob-headlines-nonce' ) ) {
			return;
		}

		if ( isset( $_POST['cob_page_headline'] ) ) {
			update_post_meta( $post_id, $this->headline_meta_key, strip_tags( $_POST['cob_page_headline'], '<br><span><em><strong>' ) );
		}

		if ( isset( $_POST['swwrc_home_mp4'] ) && ! empty( trim( $_POST['swwrc_home_mp4'] ) ) ) {
			update_post_meta( $post_id, '_swwrc_home_mp4', esc_url( $_POST['swwrc_home_mp4'] ) );
		} else {
			delete_post_meta( $post_id, '_swwrc_home_mp4' );
		}

		if ( isset( $_POST['swwrc_home_ogv'] ) && ! empty( trim( $_POST['swwrc_home_ogv'] ) ) ) {
			update_post_meta( $post_id, '_swwrc_home_ogv', esc_url( $_POST['swwrc_home_ogv'] ) );
		} else {
			delete_post_meta( $post_id, '_swwrc_home_ogv' );
		}

		if ( isset( $_POST['swwrc_home_web'] ) && ! empty( trim( $_POST['swwrc_home_web'] ) ) ) {
			update_post_meta( $post_id, '_swwrc_home_webm', esc_url( $_POST['swwrc_home_web'] ) );
		} else {
			delete_post_meta( $post_id, '_swwrc_home_webm' );
		}

		if ( isset( $_POST['swwrc_home_poster'] ) && ! empty( trim( $_POST['swwrc_home_poster'] ) ) ) {
			update_post_meta( $post_id, '_swwrc_home_poster', esc_url( $_POST['swwrc_home_poster'] ) );
		} else {
			delete_post_meta( $post_id, '_swwrc_home_poster' );
		}
	}

	/**
	 * Retrieve the assigned headline of a page.
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function get_headline( $post_id ) {
		return get_post_meta( $post_id, $this->headline_meta_key, true );
	}

	/**
	 * Retrieve the JSON data for a page containing video background information.
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public function get_json_data( $post_id ) {
		$mp4 = get_post_meta( $post_id, '_swwrc_home_mp4', true );
		$ogv = get_post_meta( $post_id, '_swwrc_home_ogv', true );
		$web = get_post_meta( $post_id, '_swwrc_home_webm', true );
		$poster = get_post_meta( $post_id, '_swwrc_home_poster', true );

		$data = array(
			'id' => 'videobg',
			'scale' => '1',
			'zIndex' => '0',
		);

		if ( ! empty( $mp4 ) ) {
			$data['mp4'] = esc_url( $mp4 );
		} else {
			$data['mp4'] = '';
		}

		if ( ! empty( $ogv ) ) {
			$data['ogv'] = esc_url( $ogv );
		} else {
			$data['ogv'] = '';
		}

		if ( ! empty( $web ) ) {
			$data['webm'] = esc_url( $web );
		} else {
			$data['webm'] = '';
		}

		if ( ! empty( $poster ) ) {
			$data['poster'] = esc_url( $poster );
		} else {
			$data['poster'] = '';
		}

		return json_encode( $data );
	}
}
$wsu_swwrc_video = new WSU_SWWRC_Video();

/**
 * Wrapper to retrieve an assigned page headline. Will fallback to the current page if
 * a post ID is not specified.
 *
 * @param int $post_id
 *
 * @return mixed
 */
function swwrc_get_page_headline( $post_id = 0 ) {
	global $wsu_swwrc_video;
	if ( is_404() ) {
		return "We're sorry. We can't find the page you're looking for.";
	}
	$post_id = absint( $post_id );
	if ( 0 === $post_id ) {
		$post_id = get_the_ID();
	}
	return $wsu_swwrc_video->get_headline( $post_id );
}

/**
 * Wrapper to retrieve assigned video data for the home page.
 *
 * @param int $post_id
 *
 * @return bool|string False if 404, JSON string if available.
 */
function swwrc_get_video_json_data( $post_id = 0 ) {
	global $wsu_swwrc_video;
	if ( is_404() ) {
		return false;
	}
	$post_id = absint( $post_id );
	if ( 0 === $post_id ) {
		$post_id = get_the_ID();
	}
	return $wsu_swwrc_video->get_json_data( $post_id );
}