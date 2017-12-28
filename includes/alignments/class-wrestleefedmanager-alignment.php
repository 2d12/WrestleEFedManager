<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_Alignment' ) ) :

class Wrestleefedmanager_Alignment {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_alignment_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_Alignment_post_type') );
		//add_action( 'save_post',  		array( $this, 'save_alignment') );
		
		add_filter ('template_include', array($this, 'display_alignment_template' ) );
	}
	
	function display_alignment_template ($template_path) {
		if ( get_post_type() == 'alignments' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-alignments.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-alignments.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_alignment_post_type() {
	 
	 $alignmentlabels = array(
        'name'                => _x( 'Alignments', 'Post Type General Name'),
        'singular_name'       => _x( 'Alignment', 'Post Type Singular Name'),
        'menu_name'           => __( 'Alignments'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Alignments'),
        'view_item'           => __( 'View Alignment'),
        'add_new_item'        => __( 'Add New Alignment'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Alignment'),
        'update_item'         => __( 'Update Alignment'),
        'search_items'        => __( 'Search Alignments'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$alignmentargs = array(
        'label'               => __( 'Alignments' ),
        'description'         => __( 'Alignments' ),
        'labels'              => $alignmentlabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'weightclass', 'division', 'gender', 'alignment' ),
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
		//'register_meta_box_cb' => array( $this, 'initialize_Alignment_post_type'),
    );
	 
		register_post_type( 'alignments', $alignmentargs);
	}
}
endif;