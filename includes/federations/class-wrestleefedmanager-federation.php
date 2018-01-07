<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_Federation' ) ) :

class Wrestleefedmanager_Federation {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_federation_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_federation_post_type') );
		add_action( 'save_post',  		array( $this, 'save_fed') );
		
		add_filter ('template_include', array($this, 'display_federation_template' ) );
	}
	
	// Our custom post type function
	function create_federation_post_type() {
	 
	 $fedlabels = array(
        'name'                => _x( 'Federations/Divisions', 'Post Type General Name'),
        'singular_name'       => _x( 'Fed/Div', 'Post Type Singular Name'),
        'menu_name'           => __( 'Feds/Divs'),
        'parent_item_colon'   => __( 'Parent Federation'),
        'all_items'           => __( 'All Feds & Divisions'),
        'view_item'           => __( 'View Federation/Division'),
        'add_new_item'        => __( 'Add New Federation/Division'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Federation/Division'),
        'update_item'         => __( 'Update Federation or Division'),
        'search_items'        => __( 'Search Feds and Divisions'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$fedargs = array(
        'label'               => __( 'Federations' ),
        'description'         => __( 'Wrestling Companies' ),
        'labels'              => $fedlabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'page-attributes', 'thumbnail', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 17,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
		'register_meta_box_cb' => array( $this, 'initialize_federation_post_type'),
    );
	 
		register_post_type( 'feds', $fedargs);
	}
	
	
	function initialize_federation_post_type() {
		/*
		add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, 
		              string $context = 'advanced', string $priority = 'default', array $callback_args = null )*/
		add_meta_box("abbreviation", "Abbreviation", array( $this, 'fed_abbr'), "feds", "normal", "low");
		add_meta_box("founded", "Founded", array( $this, 'fed_founded'), "feds", "side", "low");
		add_meta_box("closed", "Closed", array( $this, 'fed_closed'), "feds", "side", "low");
		add_meta_box("logo", "Logo", array( $this, 'fed_logo'), "feds", "normal", "low");
		add_meta_box("owner", "Owner", array( $this, 'fed_owner'), "feds", "side", "low");		
	}
	
	function fed_abbr(){
		global $post;
		$custom = get_post_custom($post->ID);
		$abbr = $custom["abbreviation"][0];
		?>
		<label>Abbr:</label>
		<input name="fed_abbreviation" type="text" value="<?php echo $abbr; ?>" />
		<?php
		}
	
	function fed_founded(){
		global $post;
		$custom = get_post_custom($post->ID);
		$founded = $custom["founded"][0];
		?>
		<label>Founded:</label>
		<input name="fed_founddate" value="<?php echo $founded; ?>" />
		<?php
	}
	
	function fed_closed(){
		global $post;
		$custom = get_post_custom($post->ID);
		$closed = $custom["closed"][0];
		?>
		<label>Closed:</label>
		<input name="fed_closedate" value="<?php echo $closed; ?>" />
		<?php
	}
	
	
	function fed_parent(){
	}
	
	function fed_logo(){
	}
	
	function fed_owner(){
		global $post;
		$custom = get_post_custom($post->ID);
		$owner = $custom["owner"][0];
		?>
		<label>Owner:</label>
		<input name="fed_owner" value="<?php echo $owner; ?>" />
		<?php
	}
	
	function save_fed(){
		global $post;
		
		update_post_meta($post->ID, "abbreviation", $_POST["fed_abbreviation"]);
		update_post_meta($post->ID, "founded", $_POST["fed_founddate"]);
		update_post_meta($post->ID, "closed", $_POST["fed_closedate"]);
		update_post_meta($post->ID, "owner", $_POST["fed_owner"]);
	}
	
	function display_federation_template ($template_path) {
		if ( get_post_type() == 'feds' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-feds.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-feds.php';
            }
        }
    }
    return $template_path;
	}
	
}

/*
//hook into the init action and call create_topics_nonhierarchical_taxonomy when it fires
 
add_action( 'init', 'create_topics_nonhierarchical_taxonomy', 0 );
 
function create_topics_nonhierarchical_taxonomy() {
 
// Labels part for the GUI
 
  $labels = array(
    'name' => _x( 'Topics', 'taxonomy general name' ),
    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Topics' ),
    'popular_items' => __( 'Popular Topics' ),
    'all_items' => __( 'All Topics' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Topic' ), 
    'update_item' => __( 'Update Topic' ),
    'add_new_item' => __( 'Add New Topic' ),
    'new_item_name' => __( 'New Topic Name' ),
    'separate_items_with_commas' => __( 'Separate topics with commas' ),
    'add_or_remove_items' => __( 'Add or remove topics' ),
    'choose_from_most_used' => __( 'Choose from the most used topics' ),
    'menu_name' => __( 'Topics' ),
  ); 
 
// Now register the non-hierarchical taxonomy like tag
 
  register_taxonomy('topics','post',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'topic' ),
  ));
}
*/
endif;