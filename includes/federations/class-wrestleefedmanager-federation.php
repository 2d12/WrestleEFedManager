<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_Federation' ) ) :

class Wrestleefedmanager_Federation {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_federation_post_type' ) ); 
		add_action( 'add_meta_boxes', 	array( $this, 'initialize_federation_post_type') );
		add_action( 'save_post',  		array( $this, 'save_fed') );
	}
	
	// Our custom post type function
	function create_federation_post_type() {
	 
	 $fedlabels = array(
        'name'                => _x( 'Federations', 'Post Type General Name'),
        'singular_name'       => _x( 'Federation', 'Post Type Singular Name'),
        'menu_name'           => __( 'Federations'),
        'parent_item_colon'   => __( 'Parent Fed'),
        'all_items'           => __( 'All Feds'),
        'view_item'           => __( 'View Fed'),
        'add_new_item'        => __( 'Add New Fed'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Fed'),
        'update_item'         => __( 'Update Fed'),
        'search_items'        => __( 'Search Feds'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$fedargs = array(
        'label'               => __( 'feds' ),
        'description'         => __( 'Federations' ),
        'labels'              => $fedlabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'author', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
	 
		register_post_type( 'feds', $fedargs);
	}
	
	
	function initialize_federation_post_type() {
		/*
		add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, 
		              string $context = 'advanced', string $priority = 'default', array $callback_args = null )*/
		add_meta_box("abbreviation", "Abbreviation", "fed_abbr", "feds", "normal", "low");
		add_meta_box("founded", "Founded", "fed_founded", "feds", "side", "low");
		add_meta_box("closed", "Closed", "fed_closed", "feds", "side", "low");
		add_meta_box("parentfed", "Parent Federation", "fed_parent", "feds", "normal", "low");
		add_meta_box("logo", "Logo", "fed_logo", "feds", "normal", "low");
		add_meta_box("owner", "Owner", "fed_owner", "feds", "side", "low");		
	}
	
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
	
	
}

endif;