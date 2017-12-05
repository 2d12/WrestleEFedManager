<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_alignment' ) ) :

class Wrestleefedmanager_alignment {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init', array ($this, 'create_alignment_taxonomy'), 0 );
	}
	
	function create_alignment_taxonomy() {
 
	// Labels part for the GUI
	 
	  $labels = array(
		'name' => _x( 'Alignments', 'taxonomy general name' ),
		'singular_name' => _x( 'Alignment', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search alignments' ),
		'popular_items' => null,
		'all_items' => __( 'All alignments' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit alignment' ), 
		'update_item' => __( 'Update alignment' ),
		'add_new_item' => __( 'Add New alignment' ),
		'new_item_name' => __( 'New alignment' ),
		'separate_items_with_commas' => __( 'Separate alignments with commas' ),
		'add_or_remove_items' => __( 'Add or remove alignments' ),
		'choose_from_most_used' => __( 'Choose from the most used alignments' ),
		'menu_name' => __( 'Alignments' ),
	  ); 
	 
	// Now register the non-hierarchical taxonomy like tag
	 
	  register_taxonomy('alignment',array(),array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_menu' => 19,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'alignment' ),
	  ));
	}	
}
endif;