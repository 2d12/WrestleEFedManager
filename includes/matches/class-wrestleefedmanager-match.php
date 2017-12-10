<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_Match' ) ) :

class Wrestleefedmanager_Match {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_match_page_type' ) ); 
		add_action( 'save_post',  		array( $this, 'save_match') );		
		add_filter ('template_include', array($this, 'display_match_template' ) );
	}
	
	function display_match_template ($template_path) {
		if ( get_post_type() == 'match' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-match.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-match.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_match_page_type() {
	 
	 $matchlabels = array(
        'name'                => _x( 'Matches', 'Post Type General Name'),
        'singular_name'       => _x( 'Match', 'Post Type Singular Name'),
        'menu_name'           => __( 'Matches'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Matches'),
        'view_item'           => __( 'View Match'),
        'add_new_item'        => __( 'Add New Match'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Match'),
        'update_item'         => __( 'Update Match'),
        'search_items'        => __( 'Search Matches'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$matchargs = array(
        'label'               => __( 'Matches' ),
        'description'         => __( 'Matches' ),
        'labels'              => $matchlabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'page-attributes',),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'weightclass', 'division', 'gender', 'title' ),
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
        'menu_position'       => 19,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
		'register_meta_box_cb' => array( $this, 'initialize_match_page_type'),
    );
	 
		register_post_type( 'match', $matchargs);
	}
	
		
	function save_match(){
		global $post;
		
		update_post_meta($post->ID, "competitors", $_POST["match_competitors"]);
		update_post_meta($post->ID, "referee", $_POST["match_referee"]);
		update_post_meta($post->ID, "rating", $_POST["match_rating"]);
		update_post_meta($post->ID, "victors", $_POST["match_victors"]);
		update_post_meta($post->ID, "time", $_POST["match_time"]);
		update_post_meta($post->ID, "finisher", $_POST["match_finisher"]);
		update_post_meta($post->ID, "titledefense", $_POST["match_defensetype"]);
	}
	
	function initialize_match_page_type() {
		add_meta_box("competitors", "Competitors (Shortcode OK)", array( $this, 'match_competitors'), "match", "normal", "low");		
		add_meta_box("results", "Results", array( $this, 'match_results'), "match", "normal", "low");
		add_meta_box("referee", "Referee", array( $this, 'match_referee'), "match", "side", "low");
		add_meta_box("rating", "Rating", array( $this, 'match_rating'), "match", "side", "low");
	}	
	function match_competitors() {
		global $post;
		$custom = get_post_custom($post->ID);
		$competitors = $custom["competitors"][0];
		?>
		<input name="match_competitors" type="text" size="150" value="<?php echo $competitors; ?>" />
		<?php
	}
	
	function match_results() {
		global $post;
		$custom = get_post_custom($post->ID);
		$victors = $custom["victors"][0];
		$time = $custom["time"][0];
		$finisher = $custom["finisher"][0];
		$titledefense = $custom["titledefense"][0];
		?>
		<table>
		<tr><td><label>Victor(s):</label></td><td><input name="match_victors" type="text" size="150" value="<?php echo $victors; ?>" /></td></tr>
		<tr><td><label>Time:</label></td><td><input name="match_time" type="text" size="150" value="<?php echo $time; ?>" /></td></tr>
		<tr><td><label>Finish:</label></td><td><input name="match_finisher" type="text" size="150" value="<?php echo $finisher; ?>" /></td></tr></table>
		<input type="radio" id="Non-Title" name="match_defensetype" value="Non-Title" <?php if ($titledefense != "Successful Defense" && $titledefense != "New Champion")echo "checked"; ?>><label for="Non-Title">Non-Title Match</label>
		<input type="radio" id="Successful" name="match_defensetype" value="Successful Defense" <?php if ($titledefense == "Successful Defense")echo "checked"; ?>><label for="Successful">Successful Defense</label>
		<input type="radio" id="NewChamp" name="match_defensetype" value="New Champion" <?php if ($titledefense == "New Champion")echo "checked"; ?>><label for="NewChamp">New Champion</label>		
		<?php
	}
	
	function match_referee() {
		global $post;
		$custom = get_post_custom($post->ID);
		$referee = $custom["referee"][0];
		?>
		<input name="match_referee" type="text" value="<?php echo $referee; ?>" />
		<?php
	}
	
	function match_rating() {
		global $post;
		$custom = get_post_custom($post->ID);
		$rating = $custom["rating"][0];
		?>
		<input name="match_rating" type="text" value="<?php echo $rating; ?>" />
		<?php
	}
	
}
endif;