<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'efedmanager_gender' ) ) :

class efedmanager_gender {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_gender_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_gender_post_type') );
		//add_action( 'save_post',  		array( $this, 'save_gender') );
		
		add_filter ('template_include', array($this, 'display_gender_template' ) );
	}
	
	function display_gender_template ($template_path) {
		if ( get_post_type() == 'genders' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-genders.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-genders.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_gender_post_type() {
	 
	 $genderlabels = array(
        'name'                => _x( 'Genders', 'Post Type General Name'),
        'singular_name'       => _x( 'Gender', 'Post Type Singular Name'),
        'menu_name'           => __( 'Genders'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Genders'),
        'view_item'           => __( 'View Gender'),
        'add_new_item'        => __( 'Add New Gender'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Gender'),
        'update_item'         => __( 'Update Gender'),
        'search_items'        => __( 'Search Genders'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$genderargs = array(
        'label'               => __( 'Genders' ),
        'description'         => __( 'Genders' ),
        'labels'              => $genderlabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'weightclass', 'gender', 'gender', 'gender' ),
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
        'menu_position'       => 33,
        //'can_export'          => true,
        //'has_archive'         => true,
        //'exclude_from_search' => false,
        //'publicly_queryable'  => true,
        'capability_type'     => 'page',
		//'register_meta_box_cb' => array( $this, 'initialize_gender_post_type'),
		'menu_icon'   		   => 'dashicons-universal-access-alt',
    );
	 
		register_post_type( 'genders', $genderargs);
	}
}
endif;