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
	 
	  register_taxonomy('weightclass','workers',array(
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
	
		
		
		/**function create_weight_class() {

	$labels = array(
		'name'                       => _x( 'Weight Classes', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Weight Class', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Weight Classes', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'weightclass', array( 'posts', 'workers' ), $args );

}*/
//add_action( 'init', 'create_weight_class', 0 );
		
		
}
endif;