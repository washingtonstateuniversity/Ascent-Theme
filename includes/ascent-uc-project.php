<?php

class Ascent_UC_Project {
	/**
	 * @var string Slug for tracking the assignment of FAA Advisors to projects.
	 */
	var $advisor_content_slug = 'faa_advisors';

	/**
	 * @var string Slug for tracking the assigment of project leads to projects.
	 */
	var $project_lead_content_slug = 'project_leads';

	/**
	 * Setup the extension.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		add_action( 'pre_get_posts', array( $this, 'modify_archive_query' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 20 );
		add_action( 'manage_wsuwp_uc_project_posts_columns', array( $this, 'manage_project_posts_columns' ), 10, 1 );
		add_action( 'manage_wsuwp_uc_project_posts_custom_column', array( $this, 'manage_project_posts_custom_column' ), 10, 2 );
		add_action( 'manage_edit-wsuwp_uc_project_sortable_columns', array( $this, 'sort_columns' ), 10, 1 );
		add_filter( 'request', array( $this, 'sort_project_number' ) );
		add_filter( 'wsuwp_uc_people_to_add_to_content', array( $this, 'modify_content_people' ), 10, 2 );
		add_filter( 'the_content', array( $this, 'add_content' ), 998, 1 );
	}

	/**
	 * Enqueue scripts required in the admin.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'wsuwp-ascent-admin', get_stylesheet_directory_uri() . '/js/admin.js', array( 'jquery-ui-autocomplete' ), false, true );
	}


	/**
	 * Modify the columns displayed in the list table for projects.
	 *
	 * @param array $post_columns Existing list of columns to display.
	 *
	 * @return array Modified list of columns to display.
	 */
	public function manage_project_posts_columns( $post_columns ) {
		unset( $post_columns['cb'] );
		unset( $post_columns['title'] );
		$new_post_columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => 'Project',
			'project_number' => 'Project Number',
		);
		$post_columns = array_merge( $new_post_columns, $post_columns );

		return $post_columns;
	}

	/**
	 * Output data associated with custom columns in the project list table.
	 *
	 * @param string $column_name Column being displayed in the row.
	 * @param int    $post_id     ID of the current row being displayed.
	 */
	public function manage_project_posts_custom_column( $column_name, $post_id ) {
		if ( 'project_number' === $column_name ) {
			$project_number = get_post_meta( $post_id, '_ascent_uc_project_number', true );
			echo esc_html( $project_number );
		}
	}

	/**
	 * Make project number a sortable field on the project list table.
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function sort_columns( $columns ) {
		$columns['project_number'] = 'project_number';
		return $columns;
	}

	/**
	 * Provide the proper sorting parameters to a project number query from the list table.
	 *
	 * @param $vars
	 *
	 * @return array
	 */
	public function sort_project_number( $vars ) {
		if ( isset( $vars['orderby'] ) && isset( $vars['post_type'] ) && 'project_number' === $vars['orderby'] && 'wsuwp_uc_project' === $vars['post_type'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_ascent_uc_project_number',
				'orderby' => 'meta_value'
			) );
		}

		return $vars;
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
		add_meta_box( 'ascent-assign-advisors', 'Assign FAA Advisors', array( $this, 'display_assign_advisors_meta_box' ), null, 'normal', 'default' );
		add_meta_box( 'ascent-assign-project-leads', 'Assign Project Leads', array( $this, 'display_assign_project_leads_meta_box' ), null, 'normal', 'default' );
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
	 * Display a meta box used to assign FAA advisors to projects.
	 *
	 * @param WP_Post $post Currently displayed post object.
	 */
	public function display_assign_advisors_meta_box( $post ) {
		global $wsuwp_university_center;

		if ( wsuwp_uc_get_object_type_slug( 'project' ) !== $post->post_type ) {
			return;
		}

		$current_advisors = get_post_meta( $post->ID, '_' . $this->advisor_content_slug . '_ids', true );
		$all_advisors = wsuwp_uc_get_all_object_data( wsuwp_uc_get_object_type_slug( 'people' ) );
		$wsuwp_university_center->display_autocomplete_input( $all_advisors, $current_advisors, $this->advisor_content_slug );
	}

	/**
	 * Display a meta box used to assign project leads to projects.
	 *
	 * @param WP_Post $post Currently displayed post object.
	 */
	public function display_assign_project_leads_meta_box( $post ) {
		global $wsuwp_university_center;

		if ( wsuwp_uc_get_object_type_slug( 'project' ) !== $post->post_type ) {
			return;
		}

		$current_leads = get_post_meta( $post->ID, '_' . $this->project_lead_content_slug . '_ids', true );
		$all_leads = wsuwp_uc_get_all_object_data( wsuwp_uc_get_object_type_slug( 'people' ) );
		$wsuwp_university_center->display_autocomplete_input( $all_leads, $current_leads, $this->project_lead_content_slug );
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

		if ( isset( $_POST['assign_' . $this->advisor_content_slug . '_ids'] ) ) {
			$people_ids = explode( ',', $_POST['assign_' . $this->advisor_content_slug . '_ids'] );
			$people_ids = wsuwp_uc_clean_post_ids( $people_ids, $this->advisor_content_slug );

			update_post_meta( $post_id, '_' . $this->advisor_content_slug . '_ids', $people_ids );
			wp_cache_delete( 'wsuwp_uc_all_' . wsuwp_uc_get_object_type_slug( 'project' ) );
			wsuwp_uc_get_all_object_data( wsuwp_uc_get_object_type_slug( 'project' ) );
		}

		if ( isset( $_POST['assign_' . $this->project_lead_content_slug . '_ids'] ) ) {
			$people_ids = explode( ',', $_POST['assign_' . $this->project_lead_content_slug . '_ids'] );
			$people_ids = wsuwp_uc_clean_post_ids( $people_ids, $this->project_lead_content_slug );

			update_post_meta( $post_id, '_' . $this->project_lead_content_slug . '_ids', $people_ids );
			wp_cache_delete( 'wsuwp_uc_all_' . wsuwp_uc_get_object_type_slug( 'project' ) );
			wsuwp_uc_get_all_object_data( wsuwp_uc_get_object_type_slug( 'project' ) );
		}

		if ( isset( $_POST['project_number'] ) ) {
			update_post_meta( $post_id, '_ascent_uc_project_number', sanitize_text_field( $_POST['project_number'] ) );
		}
	}

	/**
	 * Modify the archive query for projects.
	 *
	 * @param WP_Query $query
	 */
	public function modify_archive_query( $query ) {
		if ( ! $query->is_main_query() || is_admin() ) {
			return;
		}

		if ( $query->is_post_type_archive( 'wsuwp_uc_project' ) || $query->is_tax( 'wsuwp_uc_topics') ) {
			// We want all projects to display.
			$query->set( 'posts_per_page', '2000' );
			$query->set( 'meta_key', '_ascent_uc_project_number' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'ASC' );
		}
	}

	/**
	 * Retrieve the Ascent project number associated with a project.
	 *
	 * @param int $post_id Post ID of the project.
	 *
	 * @return string Project number associated with the project.
	 */
	public function get_project_number( $post_id ) {
		$project_number = get_post_meta( $post_id, '_ascent_uc_project_number', true );
		return $project_number;
	}

	/**
	 * Ensure FAA Advisers and Project Leads are not double listed as people in generated content.
	 *
	 * @param array $people  List of current people assigned to the project.
	 * @param int   $post_id ID of the post (project) being modified.
	 *
	 * @return array Modified list of current people assigned to the project.
	 */
	public function modify_content_people( $people, $post_id ) {
		if ( is_singular( wsuwp_uc_get_object_type_slug( 'project' ) ) ) {
			$faa_advisors = wsuwp_uc_get_object_objects( $post_id, $this->advisor_content_slug, wsuwp_uc_get_object_type_slug( 'people' ) );
			$leads = wsuwp_uc_get_object_objects( $post_id, $this->project_lead_content_slug, wsuwp_uc_get_object_type_slug( 'people' ) );
			foreach( $faa_advisors as $k => $v ) {
				unset( $people[ $k ] );
			}

			foreach( $leads as $k => $v ) {
				unset( $people[ $k ] );
			}
		}

		return $people;
	}

	/**
	 * Add FAA Advisers and Project Leads to project content.
	 *
	 * @param string $content Current object content.
	 *
	 * @return string Modified content.
	 */
	public function add_content( $content ) {
		if ( false === is_singular( wsuwp_uc_get_object_type_slug( 'project' ) ) ) {
			return $content;
		}

		$advisers = wsuwp_uc_get_object_objects( get_the_ID(), $this->advisor_content_slug, wsuwp_uc_get_object_type_slug( 'people' ) );
		$leads    = wsuwp_uc_get_object_objects( get_the_ID(), $this->project_lead_content_slug, wsuwp_uc_get_object_type_slug( 'people' ) );

		$added_html = '';

		if ( false !== $advisers && ! empty( $advisers ) ) {
			$added_html .= '<div class="wsuwp-uc-advisers"><h3>FAA Advisers</h3><ul>';
			foreach( $advisers as $adviser ) {
				$added_html .= '<li><a href="' . esc_url( $adviser['url'] ) . '">' . esc_html( $adviser['name'] ) . '</a></li>';
			}
			$added_html .= '</ul></div>';
		}

		if ( false !== $leads && ! empty( $leads ) ) {
			$added_html .= '<div class="wsuwp-uc-project-leads"><h3>Project Leads</h3><ul>';
			foreach( $leads as $lead ) {
				$added_html .= '<li><a href="' . esc_url( $lead['url'] ) . '">' . esc_html( $lead['name'] ) . '</a></li>';
			}
			$added_html .= '</ul></div>';
		}

		return $content . $added_html;
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