<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_WeightClass' ) ) :

class Wrestleefedmanager_WeightClass {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init', array ($this, 'create_weightclass_taxonomy'), 0 );
	}
	
	function create_weightclass_taxonomy() {
 
	// Labels part for the GUI
	 
	  $labels = array(
		'name' => _x( 'Weight Classes', 'taxonomy general name' ),
		'singular_name' => _x( 'Weight Class', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Weight Classes' ),
		'popular_items' => null,
		'all_items' => __( 'All Weight Classes' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Weight Class' ), 
		'update_item' => __( 'Update Weight Class' ),
		'add_new_item' => __( 'Add New Weight Class' ),
		'new_item_name' => __( 'New Weight Class' ),
		'separate_items_with_commas' => __( 'Separate weight classes with commas' ),
		'add_or_remove_items' => __( 'Add or remove weight classes' ),
		'choose_from_most_used' => __( 'Choose from the most used weight classes' ),
		'menu_name' => __( 'Weight Classes' ),
	  ); 
	 
	// Now register the non-hierarchical taxonomy like tag
	 
	  register_taxonomy('weightclass',array(),array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_menu' => 19,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'weightclass' ),
	  ));
	}	
}
endif;