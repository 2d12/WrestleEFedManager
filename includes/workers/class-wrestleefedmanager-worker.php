<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_Worker' ) ) :

class Wrestleefedmanager_Worker {
	
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
        'supports'            => array( 'title', 'editor', 'author', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'alignment', 'weightclass', 'division', 'position' ),
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
        'menu_position'       => 18,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
		'register_meta_box_cb' => array( $this, 'initialize_worker_post_type'),
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

		add_meta_box("portrait", "Portrait", array( $this, 'worker_portrait'), "workers", "side", "low");		
		add_meta_box("alignment", "Alignment", array( $this, 'worker_alignment'), "workers", "side", "low");		
		add_meta_box("weightclass", "Weight Class", array( $this, 'worker_weightclass'), "workers", "side", "low");
		add_meta_box("division", "Division", array( $this, 'worker_division'), "workers", "side", "low");		
		add_meta_box("birthday", "Birthday", array( $this, 'worker_birthday'), "workers", "side", "low");		
		add_meta_box("height", "Height", array( $this, 'worker_height'), "workers", "side", "low");		
		add_meta_box("weight", "Weight", array( $this, 'worker_weight'), "workers", "side", "low");		
		add_meta_box("gender", "Gender", array( $this, 'worker_gender'), "workers", "side", "low");		
		add_meta_box("position", "Staff Position", array( $this, 'worker_position'), "workers", "side", "low");		
		}
	
	function worker_aka() {}
	function worker_theme() {}
	function worker_signature() {}
	function worker_associates() {}
	function worker_portrait() {}
	function worker_alignment() {}
	function worker_weightclass() {}
	function worker_division() {}
	function worker_birthday() {}
	function worker_height() {}
	function worker_weight() {}
	function worker_gender() {}
	function worker_position() {}
	/*
	
	function fed_abbr(){
		global $post;
		$custom = get_post_custom($post->ID);
		$abbr = $custom["abbreviation"][0];
		?>
		<label>Abbr:</label>
		<input name="fed_abbreviation" type="text" value="<?php echo $abbr; ?>" />
		<?php
		}
	
	function fed_founded(){
		global $post;
		$custom = get_post_custom($post->ID);
		$founded = $custom["founded"][0];
		?>
		<label>Founded:</label>
		<input name="fed_founddate" value="<?php echo $founded; ?>" />
		<?php
	}
	
	function fed_closed(){
		global $post;
		$custom = get_post_custom($post->ID);
		$closed = $custom["closed"][0];
		?>
		<label>Closed:</label>
		<input name="fed_closedate" value="<?php echo $closed; ?>" />
		<?php
	}
	
	
	function fed_parent(){
	}
	
	function fed_logo(){
	}
	
	function fed_owner(){
		global $post;
		$custom = get_post_custom($post->ID);
		$owner = $custom["owner"][0];
		?>
		<label>Owner:</label>
		<input name="fed_owner" value="<?php echo $owner; ?>" />
		<?php
	}
	
	function save_fed(){
		global $post;
		
		update_post_meta($post->ID, "abbreviation", $_POST["fed_abbreviation"]);
		update_post_meta($post->ID, "founded", $_POST["fed_founddate"]);
		update_post_meta($post->ID, "closed", $_POST["fed_closedate"]);
		update_post_meta($post->ID, "owner", $_POST["fed_owner"]);
	}
	
	function display_federation_template ($template_path) {
		if ( get_post_type() == 'feds' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-feds.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-feds.php';
            }
        }
    }
    return $template_path;
	}
	*/
}
endif;