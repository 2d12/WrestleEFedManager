<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_Title' ) ) :

class Wrestleefedmanager_Title {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init', array ($this, 'create_title_taxonomy'), 0 );
	}
	
	function create_title_taxonomy() {
 
	// Labels part for the GUI
	 
	  $labels = array(
		'name' => _x( 'Titles', 'taxonomy general name' ),
		'singular_name' => _x( 'Title', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Titles' ),
		'popular_items' => null,
		'all_items' => __( 'All Titles' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Title' ), 
		'update_item' => __( 'Update Title' ),
		'add_new_item' => __( 'Add New Title' ),
		'new_item_name' => __( 'New Title' ),
		'separate_items_with_commas' => __( 'Separate titles with commas' ),
		'add_or_remove_items' => __( 'Add or remove titles' ),
		'choose_from_most_used' => __( 'Choose from the most used titles' ),
		'menu_name' => __( 'Titles' ),
	  ); 
	 
	// Now register the non-hierarchical taxonomy like tag
	 
	  register_taxonomy('title',array(),array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_menu' => 19,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'title' ),
	  ));
	}	
}
endif;