<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_Division' ) ) :

class Wrestleefedmanager_Division {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_division_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_Division_post_type') );
		//add_action( 'save_post',  		array( $this, 'save_Division') );
		
		add_filter ('template_include', array($this, 'display_division_template' ) );
	}
	
	function display_division_template ($template_path) {
		if ( get_post_type() == 'Divisions' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-divisions.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-divisions.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_division_post_type() {
	 
	 $divisionlabels = array(
        'name'                => _x( 'Divisions', 'Post Type General Name'),
        'singular_name'       => _x( 'Division', 'Post Type Singular Name'),
        'menu_name'           => __( 'Divisions'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Divisions'),
        'view_item'           => __( 'View Division'),
        'add_new_item'        => __( 'Add New Division'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Division'),
        'update_item'         => __( 'Update Division'),
        'search_items'        => __( 'Search Divisions'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$divisionargs = array(
        'label'               => __( 'Divisions' ),
        'description'         => __( 'Divisions' ),
        'labels'              => $divisionlabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'weightclass', 'Division', 'gender', 'Division' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        //'show_ui'             => true,
        //'show_in_menu'        => true,
        //'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 18,
        //'can_export'          => true,
        //'has_archive'         => true,
        //'exclude_from_search' => false,
        //'publicly_queryable'  => true,
        'capability_type'     => 'page',
		//'register_meta_box_cb' => array( $this, 'initialize_Division_post_type'),
    );
	 
		register_post_type( 'divisions', $divisionargs);
	}
}
endif;