<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'efedmanager_Team' ) ) :

class efedmanager_Team {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_team_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_team_post_type') );
		add_action( 'save_post',  		array( $this, 'save_team') );
		
		add_filter ('template_include', array($this, 'display_team_template' ) );
	}
	
	function display_team_template ($template_path) {
		if ( get_post_type() == 'teams' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-teams.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-teams.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_team_post_type() {
	 
	 $teamlabels = array(
        'name'                => _x( 'Teams', 'Post Type General Name'),
        'singular_name'       => _x( 'Team', 'Post Type Singular Name'),
        'menu_name'           => __( 'Teams'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Teams'),
        'view_item'           => __( 'View Team'),
        'add_new_item'        => __( 'Add New Team'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Team'),
        'update_item'         => __( 'Update Team'),
        'search_items'        => __( 'Search Teams'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$teamargs = array(
        'label'               => __( 'Teams' ),
        'description'         => __( 'Employees' ),
        'labels'              => $teamlabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'thumbnail', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'weightclass', 'division', 'gender', 'alignment' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 28,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
		'register_meta_box_cb' => array( $this, 'initialize_team_post_type'),
		'menu_icon'   		   => 'dashicons-groups',
    );
	 
		register_post_type( 'teams', $teamargs);
	}
	
	
	function initialize_team_post_type() {
		/*
		add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, 
		              string $context = 'advanced', string $priority = 'default', array $callback_args = null )*/
		add_meta_box("members", "Membership", array($this, 'team_members'), "teams", "normal", "low");
		add_meta_box("theme", "Theme Song", array( $this, 'team_theme'), "teams", "normal", "low");
		add_meta_box("signatures", "Signature and Finishing Moves", array( $this, 'team_signature'), "teams", "normal", "low");
		//add_meta_box("portrait", "Portrait", array( $this, 'team_portrait'), "teams", "side", "low");
		add_meta_box("alignment", "Alignment", array( $this, 'team_alignment'), "teams", "side", "low");
		add_meta_box("division", "Company/Division", array( $this, 'team_division'), "teams", "side", "low");
	}
	
	function team_members()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$competitors = unserialize($custom["team_competitors"][0]);
		$associates =  unserialize($custom["associates"][0]);
		
		echo '<table width="100%"><tr><th>Competitors</th><th>Associates</th></tr><tr><td>';
		efed_select_from_entries('team_competitors', 'workers', $competitors, true);
		echo '</td><td>';
		efed_select_from_entries('team_associates', 'workers', $associates, true);
		echo '</td></tr></table>';
		echo 'NOTE: Workers selected under Competitors will have matches and wins count for them if team is selected for a match.<br />';
	}
	
	function team_theme() {
		global $post;
		$custom = get_post_custom($post->ID);
		$themename = $custom["themename"][0];
		$themeartist = $custom["themeartist"][0];
		$themelink = $custom["themelink"][0];
		
		?>
		<table style="width:100%;box-sizing:border-box;">
		<tr><td><label>Name:</label></td><td><input name="team_theme_name" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $themename; ?>" /></td></tr>
		<tr><td><label>Artist:</label></td><td><input name="team_theme_artist" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $themeartist; ?>" /></td></tr>
		<tr><td><label>Link:</label></td><td><input name="team_theme_link" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $themelink; ?>" /></td></tr>
		</table>
		<?php
	}
	
	function team_alignment()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$align = $custom["alignment"][0];
		efed_select_from_entries('team_alignment', 'alignments', $align);
	}
	
	function team_division()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$div = unserialize($custom["federation"][0]);
		efed_select_from_entries('team_division', 'feds', $div, true, true);
	}
	
	function team_signature() {
		global $post;
		$custom = get_post_custom($post->ID);
		$signatures = $custom["signatures"][0];
		?>
		<input name="team_signatures" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $signatures; ?>" />
		<?php
	}
	
	function team_associates() {
		global $post;
		$custom = get_post_custom($post->ID);
		$associates = $custom["associates"][0];
		?>
		<input name="team_associates" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $associates; ?>" />
		<?php
	}
	
	function save_team(){
		global $post;
		if (count ($_POST) <= 0)
			return;

		update_post_meta($post->ID, "signatures", $_POST["team_signatures"]);
		update_post_meta($post->ID, "themename", $_POST["team_theme_name"]);
		update_post_meta($post->ID, "themeartist", $_POST["team_theme_artist"]);
		update_post_meta($post->ID, "themelink", $_POST["team_theme_link"]);
		update_post_meta($post->ID, "alignment", $_POST["team_alignment"]);
		
		if ( !add_post_meta( $post->ID, "team_competitors", $_POST["team_competitors"], true) ) {
			update_post_meta($post->ID, "team_competitors", $_POST["team_competitors"]);
		}
		
		if ( !add_post_meta( $post->ID, "associates", $_POST["team_associates"], true) ) {
			update_post_meta($post->ID, "associates", $_POST["team_associates"]);
		}
		
		if ( ! add_post_meta( $post->ID, "federation", $_POST["team_division"], true ) ) { 
			update_post_meta( $post->ID, "federation", $_POST["team_division"] );
		}
	}
}
endif;