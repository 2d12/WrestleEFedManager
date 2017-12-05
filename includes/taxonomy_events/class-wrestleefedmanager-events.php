<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_event' ) ) :

class Wrestleefedmanager_event {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init', array ($this, 'create_event_taxonomy'), 0 );
	}
	
	function create_event_taxonomy() {
 
	// Labels part for the GUI
	 
	  $labels = array(
		'name' => _x( 'Events', 'taxonomy general name' ),
		'singular_name' => _x( 'Event', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Events' ),
		'popular_items' => null,
		'all_items' => __( 'All Events' ),
		'parent_item' => __( 'Parent Event' ),
		'parent_item_colon' => __( 'Parent Event:' ),    
		'edit_item' => __( 'Edit Events' ), 
		'update_item' => __( 'Update Event' ),
		'add_new_item' => __( 'Add New Event' ),
		'new_item_name' => __( 'New Event' ),
		'menu_name' => __( 'Events' ),
	  ); 
	 
	// Now register the non-hierarchical taxonomy like tag
	 
	  register_taxonomy('event',array(),array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_menu' => 19,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'event' ),
	  ));
	}	
}
endif;