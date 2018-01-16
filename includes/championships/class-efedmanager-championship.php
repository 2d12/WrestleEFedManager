<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'efedmanager_Championship' ) ) :

class efedmanager_Championship {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_championship_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_worker_post_type') );
		add_action( 'save_post',  		array( $this, 'save_championship') );
		
		add_filter ('template_include', array($this, 'display_championship_template' ) );
	}
	
	function display_championship_template ($template_path) {
		if ( get_post_type() == 'championship' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-championship.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-championship.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_championship_post_type() {
	 
	 $championshiplabels = array(
        'name'                => _x( 'Championships', 'Post Type General Name'),
        'singular_name'       => _x( 'Championship', 'Post Type Singular Name'),
        'menu_name'           => __( 'Championships'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Championships'),
        'view_item'           => __( 'View Championship'),
        'add_new_item'        => __( 'Add New Championship'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Championship'),
        'update_item'         => __( 'Update Championship'),
        'search_items'        => __( 'Search Championships'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$championshipargs = array(
        'label'               => __( 'championship' ),
        'description'         => __( 'championships' ),
        'labels'              => $championshiplabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'thumbnail',),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        //'taxonomies'          => array( 'weightclass', 'division', 'gender', 'alignment' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 31,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
		'register_meta_box_cb' => array( $this, 'initialize_championship_post_type'),
		'menu_icon'   		   => 'dashicons-awards',

    );
	 
		register_post_type( 'championship', $championshipargs);
	}
	
	
	function initialize_championship_post_type() {
		/*
		add_meta_box( string $id, string $championship, callable $callback, string|array|WP_Screen $screen = null, 
		              string $context = 'advanced', string $priority = 'default', array $callback_args = null )*/
		add_meta_box("feddiv", "Federation/Division", array( $this, 'championship_div'), "championship", "normal", "low");
		add_meta_box("type", "Championship Type", array( $this, 'championship_type'), "championship", "normal", "low");
		add_meta_box("weightclass", "Weight Class", array( $this, 'championship_wc'), "championship", "normal", "low");
		add_meta_box("gender", "Gender", array( $this, 'championship_gender'), "championship", "normal", "low");
		
		}

	function championship_div()
	{
		global $post;
		$fed = array();
		$custom = get_post_custom($post->ID);
		if (array_key_exists("federations", $custom))
			$fed = unserialize($custom["federations"][0]);
		efed_select_from_entries('championship_federation', 'feds', $fed, true, true);
	}
	
	function championship_type()
	{
		global $post;
		$type = "";
		$custom = get_post_custom($post->ID);
		if (array_key_exists("type", $custom))
			$type = $custom["type"][0];
		?>
		<label for="championship_type">Type</label>
		<select name='championship_type'>
			<option <?php if ($type != "singles" && strpos($type, "tag") == false) echo 'selected'; ?>>&nbsp;</option>
			<option value="singles" <?php if ($type=="singles") echo 'selected';?>>Singles Title</option>
			<option value="tag" <?php if ($type=="tag") echo 'selected';?>>Tag Team Title</option>
			<option value="6tag" <?php if ($type=="6tag") echo 'selected';?>>6-Man Tag Team Title</option>
			<option value="8tag" <?php if ($type=="8tag") echo 'selected';?>>8-Man Tag Team Title</option>
		</select>
		<?php
		
	}
	
	function championship_wc()
	{
		global $post;
		$wc = array();
		$custom = get_post_custom($post->ID);
		if (array_key_exists("weightclasses", $custom))
			$wc = unserialize($custom["weightclasses"][0]);
		efed_select_from_entries('championship_weightclass', 'weightclasses', $wc, true);
	}
	
	function championship_gender()
	{
		global $post;
		$gender = array();
		$custom = get_post_custom($post->ID);
		if (array_key_exists("genders", $custom))
			$gender = unserialize($custom["genders"][0]);
		efed_select_from_entries('championship_gender', 'genders', $gender, true);
	}
	
	function save_championship(){
		global $post;
		if (count ($_POST) <= 0)
			return;

		
		update_post_meta($post->ID, "federations", $_POST["championship_federation"]);
		update_post_meta($post->ID, "weightclasses", $_POST["championship_weightclass"]);
		update_post_meta($post->ID, "genders", $_POST["championship_gender"]);
		update_post_meta($post->ID, "type", $_POST["championship_type"]);
	}

}
endif;