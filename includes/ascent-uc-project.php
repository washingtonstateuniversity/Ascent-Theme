<?php

class Ascent_UC_Project {
	/**
	 * Setup the extension.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		add_action( 'pre_get_posts', array( $this, 'modify_archive_query' ) );
	}

	/**
	 * Add meta boxes to the appropriate display.
	 *
	 * @param string $post_type
	 */
	public function add_meta_boxes( $post_type ) {
		if ( 'wsuwp_uc_project' !== $post_type ) {
			return;
		}

		add_meta_box( 'ascent-project-number', 'Project Number', array( $this, 'display_project_number_meta_box' ), null, 'normal', 'default' );
	}

	/**
	 * Display the meta box used to capture a project number.
	 *
	 * @param WP_Post $post
	 */
	public function display_project_number_meta_box( $post ) {
		$project_number = get_post_meta( $post->ID, '_ascent_uc_project_number', true );

		wp_nonce_field( 'ascent-project-nonce', '_ascent_project_nonce' );
		?>
		<label for="project-number">Project Number:</label>
		<input type="text" id="project-number" name="project_number" value="<?php echo esc_attr( $project_number ); ?>" />
	<?php
	}

	/**
	 * Save post meta for the UC project type.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public function save_post( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'wsuwp_uc_project' !== $post->post_type ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( ! isset( $_POST['_ascent_project_nonce'] ) || false === wp_verify_nonce( $_POST['_ascent_project_nonce'], 'ascent-project-nonce' ) ) {
			return;
		}

		if ( ! isset( $_POST['project_number'] ) ) {
			return;
		}

		update_post_meta( $post_id, '_ascent_uc_project_number', sanitize_text_field( $_POST['project_number'] ) );
	}

	/**
	 * Modify the archive query for projects.
	 *
	 * @param $query
	 */
	public function modify_archive_query( $query ) {
		if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'wsuwp_uc_project' ) ) {
			// We want all projects to display.
			$query->set( 'posts_per_page', '2000' );
			$query->set( 'meta_key', '_ascent_uc_project_number' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'ASC' );
		}
	}

	public function get_project_number( $post_id ) {
		$project_number = get_post_meta( $post_id, '_ascent_uc_project_number', true );
		return $project_number;
	}
}
$ascent_uc_project = new Ascent_UC_Project();

function ascent_get_project_number( $post_id = 0 ) {
	global $ascent_uc_project;

	if ( 0 === $post_id ) {
		$post_id = get_the_ID();
	}
	return $ascent_uc_project->get_project_number( $post_id );

}