<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'efedmanager_Worker' ) ) :

class efedmanager_Worker {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_worker_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_worker_post_type') );
		add_action( 'save_post',  		array( $this, 'save_worker') );
		
		add_filter ('template_include', array($this, 'display_worker_template' ) );
	}
	
	function display_worker_template ($template_path) {
		if ( get_post_type() == 'workers' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-workers.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-workers.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_worker_post_type() {
	 
	 $workerlabels = array(
        'name'                => _x( 'Workers', 'Post Type General Name'),
        'singular_name'       => _x( 'Worker', 'Post Type Singular Name'),
        'menu_name'           => __( 'Workers'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Workers'),
        'view_item'           => __( 'View Worker'),
        'add_new_item'        => __( 'Add New Worker'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Worker'),
        'update_item'         => __( 'Update Worker'),
        'search_items'        => __( 'Search Workers'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$workerargs = array(
        'label'               => __( 'Workers' ),
        'description'         => __( 'Employees' ),
        'labels'              => $workerlabels,
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
        'menu_position'       => 27,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
		'register_meta_box_cb' => array( $this, 'initialize_worker_post_type'),
		'menu_icon'   		   => 'dashicons-businessman',
    );
	 
		register_post_type( 'workers', $workerargs);
	}
	
	
	function initialize_worker_post_type() {
		/*
		add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, 
		              string $context = 'advanced', string $priority = 'default', array $callback_args = null )*/
		add_meta_box("aka", "AKA", array( $this, 'worker_aka'), "workers", "normal", "low");
		add_meta_box("theme", "Theme Song", array( $this, 'worker_theme'), "workers", "normal", "low");
		add_meta_box("signatures", "Signature and Finishing Moves", array( $this, 'worker_signature'), "workers", "normal", "low");
		add_meta_box("associates", "Associates", array( $this, 'worker_associates'), "workers", "normal", "low");
		//add_meta_box("portrait", "Portrait", array( $this, 'worker_portrait'), "workers", "side", "low");
		add_meta_box("weightclass", "Weight Class", array( $this, 'worker_weightclass'), "workers", "side", "low");
		add_meta_box("gender", "Gender", array( $this, 'worker_gender'), "workers", "side", "low");
		add_meta_box("alignment", "Alignment", array( $this, 'worker_alignment'), "workers", "side", "low");
		add_meta_box("division", "Company/Division", array( $this, 'worker_division'), "workers", "side", "low");
		
		add_meta_box("birthday", "Birthday", array( $this, 'worker_birthday'), "workers", "side", "low");		
		add_meta_box("height", "Height", array( $this, 'worker_height'), "workers", "side", "low");		
		add_meta_box("weight", "Weight", array( $this, 'worker_weight'), "workers", "side", "low");		
		add_meta_box("position", "Staff Position", array( $this, 'worker_position'), "workers", "side", "low");	

	}
	
	function worker_aka() {
		global $post;
		$aka = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("aka", $custom))
			$aka = $custom["aka"][0];
		?>
		<input name="worker_aka" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $aka; ?>" />
		<?php
	}
	function worker_theme() {
		global $post;
		$themename = "";
		$themeartist = "";
		$themelink = "";
		
		$custom = get_post_custom($post->ID);
		
		if (array_key_exists("themename", $custom))
			$themename = $custom["themename"][0];
		if (array_key_exists("themeartist", $custom))
			$themeartist = $custom["themeartist"][0];
		if (array_key_exists("themelink", $custom))
			$themelink = $custom["themelink"][0];
		
		?>
		<table style="width:100%;box-sizing:border-box;">
		<tr><td><label>Name:</label></td><td><input name="worker_theme_name" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $themename; ?>" /></td></tr>
		<tr><td><label>Artist:</label></td><td><input name="worker_theme_artist" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $themeartist; ?>" /></td></tr>
		<tr><td><label>Link:</label></td><td><input name="worker_theme_link" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $themelink; ?>" /></td></tr>
		</table>
		<?php
	}
	
	function worker_weightclass()
	{
		global $post;
		$wc = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("weightclass", $custom))
			$wc = $custom["weightclass"][0];
		//echo 'Saved value : ' . $wc . '<br />';
		efed_select_from_entries('worker_weightclass', 'weightclasses', $wc);
	}
	function worker_alignment()
	{
		global $post;
		$align = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("walignment", $custom))
			$align = $custom["walignment"][0];
		efed_select_from_entries('worker_alignment', 'alignments', $align);
	}
	function worker_gender()
	{
		global $post;
		$gender = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("gender", $custom))
			$gender = $custom["gender"][0];
		efed_select_from_entries('worker_gender', 'genders', $gender);
	}
	function worker_division()
	{
		global $post;
		$div = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("federation", $custom))
			$div = unserialize($custom["federation"][0]);
		efed_select_from_entries('worker_division', 'feds', $div, true, true);
	}
	function worker_signature() {
		global $post;
		$signatures = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("signatures", $custom))
			$signatures = $custom["signatures"][0];
		?>
		<input name="worker_signatures" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $signatures; ?>" />
		<?php
	}
	function worker_associates() {
		global $post;
		$associates = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("associates", $custom))
			$associates = $custom["associates"][0];
		?>
		<input name="worker_associates" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $associates; ?>" />
		<?php
	}
	function worker_birthday() {
		global $post;
		$birth = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("birth", $custom))
			$birth = $custom["birth"][0];
		?>
		<input name="worker_birth" type="text" value="<?php echo $birth; ?>" />
		<?php
	}
	function worker_height() {
		global $post;
		$height = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("height", $custom))
			$height = $custom["height"][0];
		?>
		<input name="worker_height" type="text" value="<?php echo $height; ?>" />
		<?php
	}
	function worker_weight() {
		global $post;
		$weight = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("weight", $custom))
			$weight = $custom["weight"][0];
		?>
		<input name="worker_weight" type="text" value="<?php echo $weight; ?>" />
		<?php
	}
	
	function worker_position() {
		global $post;
		$staffpos = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("staffpos", $custom))
			$staffpos = $custom["staffpos"][0];
		?>
		<input name="worker_staffpos" type="text" value="<?php echo $staffpos; ?>" />
		<?php
	}
	
	function save_worker(){
		global $post;
		if (count ($_POST) <= 0)
			return;
		if ( !add_post_meta( $post->ID, "aka", $_POST["worker_aka"], true) ) {
			update_post_meta($post->ID, "aka", $_POST["worker_aka"]);
		}
		if ( !add_post_meta( $post->ID, "birth", $_POST["worker_birth"], true) ) {
			update_post_meta($post->ID, "birth", $_POST["worker_birth"]);
		}		
		if ( !add_post_meta( $post->ID, "height", $_POST["worker_height"], true) ) {
			update_post_meta($post->ID, "height", $_POST["worker_height"]);
		}		
		if ( !add_post_meta( $post->ID, "weight", $_POST["worker_weight"], true) ) {
			update_post_meta($post->ID, "weight", $_POST["worker_weight"]);
		}		
		if ( !add_post_meta( $post->ID, "associates", $_POST["worker_associates"], true) ) {
			update_post_meta($post->ID, "associates", $_POST["worker_associates"]);
		}		
		if ( !add_post_meta( $post->ID, "signatures", $_POST["worker_signatures"], true) ) {
			update_post_meta($post->ID, "signatures", $_POST["worker_signatures"]);
		}		
		if ( !add_post_meta( $post->ID, "themename", $_POST["worker_theme_name"], true) ) {
			update_post_meta($post->ID, "themename", $_POST["worker_theme_name"]);
		}		
		if ( !add_post_meta( $post->ID, "themeartist", $_POST["worker_theme_artist"], true) ) {
			update_post_meta($post->ID, "themeartist", $_POST["worker_theme_artist"]);
		}		
		if ( !add_post_meta( $post->ID, "themelink", $_POST["worker_theme_link"], true) ) {
			update_post_meta($post->ID, "themelink", $_POST["worker_theme_link"]);
		}		
		if ( !add_post_meta( $post->ID, "position", $_POST["worker_staffpos"], true) ) {
			update_post_meta($post->ID, "position", $_POST["worker_staffpos"]);
		}		
		if ( !add_post_meta( $post->ID, "weightclass", $_POST["worker_weightclass"], true) ) {
			update_post_meta($post->ID, "weightclass", $_POST["worker_weightclass"]);
		}		
		if ( !add_post_meta( $post->ID, "gender", $_POST["worker_gender"], true) ) {
			update_post_meta($post->ID, "gender", $_POST["worker_gender"]);
		}		
		if ( !add_post_meta( $post->ID, "walignment", $_POST["worker_alignment"], true) ) {
			update_post_meta($post->ID, "walignment", $_POST["worker_alignment"]);
		}				
		if ( ! add_post_meta( $post->ID, "federation", $_POST["worker_division"], true ) ) { 
			update_post_meta( $post->ID, "federation", $_POST["worker_division"] );
		}

	}
}
endif;