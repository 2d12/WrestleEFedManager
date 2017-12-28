<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_weightclass' ) ) :

class Wrestleefedmanager_weightclass {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_weightclass_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_weightclass_post_type') );
		//add_action( 'save_post',  		array( $this, 'save_weightclass') );
		
		add_filter ('template_include', array($this, 'display_weightclass_template' ) );
	}
	
	function display_weightclass_template ($template_path) {
		if ( get_post_type() == 'weightclasss' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-weightclasss.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-weightclasss.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_weightclass_post_type() {
	 
	 $weightclasslabels = array(
        'name'                => _x( 'Weight Classes', 'Post Type General Name'),
        'singular_name'       => _x( 'Weight Class', 'Post Type Singular Name'),
        'menu_name'           => __( 'Weight Classes'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Weight Classes'),
        'view_item'           => __( 'View Weight Class'),
        'add_new_item'        => __( 'Add New Weight Class'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Weight Class'),
        'update_item'         => __( 'Update Weight Class'),
        'search_items'        => __( 'Search Weight Classes'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$weightclassargs = array(
        'label'               => __( 'weightclass' ),
        'description'         => __( 'weightclass' ),
        'labels'              => $weightclasslabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'weightclass', 'weightclass', 'gender', 'weightclass' ),
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
		//'register_meta_box_cb' => array( $this, 'initialize_weightclass_post_type'),
    );
	 
		register_post_type( 'weightclasses', $weightclassargs);
	}
}
endif;