<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_gender' ) ) :

class Wrestleefedmanager_gender {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init', array ($this, 'create_gender_taxonomy'), 0 );
	}
	
	function create_gender_taxonomy() {
 
	// Labels part for the GUI
	 
	  $labels = array(
		'name' => _x( 'Genders', 'taxonomy general name' ),
		'singular_name' => _x( 'Gender', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search genders' ),
		'popular_items' => null,
		'all_items' => __( 'All genders' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit gender' ), 
		'update_item' => __( 'Update gender' ),
		'add_new_item' => __( 'Add New gender' ),
		'new_item_name' => __( 'New gender' ),
		'separate_items_with_commas' => __( 'Separate genders with commas' ),
		'add_or_remove_items' => __( 'Add or remove genders' ),
		'choose_from_most_used' => __( 'Choose from the most used genders' ),
		'menu_name' => __( 'Genders' ),
	  ); 
	 
	// Now register the non-hierarchical taxonomy like tag
	 
	  register_taxonomy('gender',array(),array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_menu' => 19,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'gender' ),
	  ));
	}	
}
endif;