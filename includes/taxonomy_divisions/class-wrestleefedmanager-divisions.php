<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_division' ) ) :

class Wrestleefedmanager_division {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init', array ($this, 'create_division_taxonomy'), 0 );
	}
	
	function create_division_taxonomy() {
 
	// Labels part for the GUI
	 
	  $labels = array(
		'name' => _x( 'Divisions', 'taxonomy general name' ),
		'singular_name' => _x( 'Division', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Divisions' ),
		'popular_items' => null,
		'all_items' => __( 'All Divisions' ),
		'parent_item' => __( 'Parent Division' ),
		'parent_item_colon' => __( 'Parent Division:' ),    
		'edit_item' => __( 'Edit Division' ), 
		'update_item' => __( 'Update Weight Class' ),
		'add_new_item' => __( 'Add New Division' ),
		'new_item_name' => __( 'New Division' ),
		'menu_name' => __( 'Divisions' ),
	  ); 
	 
	// Now register the non-hierarchical taxonomy like tag
	 
	  register_taxonomy('division',array(),array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_menu' => 19,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'division' ),
	  ));
	}	
}
endif;