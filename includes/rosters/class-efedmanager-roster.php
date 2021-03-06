<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'efedmanager_Roster' ) ) :

class efedmanager_Roster {
	
	/**
     * Constructor
     */
    public function __construct() {
		// Hooking up our function to theme setup
		add_action( 'init',       		array( $this, 'create_roster_post_type' ) ); 
		//add_action( 'add_meta_boxes', 	array( $this, 'initialize_worker_post_type') );
		add_action( 'save_post',  		array( $this, 'save_roster') );
		
		add_filter ('template_include', array($this, 'display_roster_template' ) );
	}
	
	function display_roster_template ($template_path) {
		if ( get_post_type() == 'roster' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-roster.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-roster.php';
				}
			}
		}
	return $template_path;
	}
    
	
	// Our custom post type function
	function create_roster_post_type() {
	 
	 $rosterlabels = array(
        'name'                => _x( 'Roster', 'Post Type General Name'),
        'singular_name'       => _x( 'Roster', 'Post Type Singular Name'),
        'menu_name'           => __( 'Roster'),
        'parent_item_colon'   => __( null),
        'all_items'           => __( 'All Rosters'),
        'view_item'           => __( 'View Roster'),
        'add_new_item'        => __( 'Add New Roster'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit Roster'),
        'update_item'         => __( 'Update Roster'),
        'search_items'        => __( 'Search Rosters'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash'),
    );
	
	$rosterargs = array(
        'label'               => __( 'Roster' ),
        'description'         => __( 'Rosters' ),
        'labels'              => $rosterlabels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', ),
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
        'menu_position'       => 29,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
		'register_meta_box_cb' => array( $this, 'initialize_roster_post_type'),
		'menu_icon'   		   => 'dashicons-id',
    );
	 
		register_post_type( 'roster', $rosterargs);
	}
	
	
	function initialize_roster_post_type() {
		add_meta_box("filters", "Filters", array( $this, 'roster_filters'), "roster", "normal", "low");
		}

    /**
     *
     */
    function roster_filters() {
		global $post;
		$team = "";
		$fed = array();
		$wc = array();
		$gender = array();
		$align = array();
		$showfed = false;
		$showwc = false;
		$showgender = false;
		$showalign = false;
		
		$custom = get_post_custom($post->ID);
		if (array_key_exists("team", $custom))
			$team = $custom["team"][0];
		if (array_key_exists("fedfilter", $custom))
			$fed = unserialize($custom["fedfilter"][0]);
		if (array_key_exists("wcfilter", $custom))
			$wc = unserialize($custom["wcfilter"][0]);
		if (array_key_exists("genderfilter", $custom))
			$gender = unserialize($custom["genderfilter"][0]);
		if (array_key_exists("alignfilter", $custom))
			$align = unserialize($custom["alignfilter"][0]);
		if (array_key_exists("showfed", $custom))
			$showfed = $custom["showfed"][0];
		if (array_key_exists("showwc", $custom))
			$showwc = $custom["showwc"][0];
		if (array_key_exists("showgender", $custom))
			$showgender = $custom["showgender"][0];
		if (array_key_exists("showalign", $custom))
			$showalign = $custom["showalign"][0];

		?>
		<table>
		<tr><td colspan="2">		
		<input type="radio" id="Individual" name="roster_team" value="individual" <?php if ($team == "individual")echo "checked"; ?>><label for="Individual">Individual</label>
		<input type="radio" id="Teams/Stables" name="roster_team" value="team" <?php if ($team == "team")echo "checked"; ?>><label for="Teams/Stables">Teams/Stables</label>
		<input type="radio" id="All" name="roster_team" value="all" <?php if ($team != "individual" && $team != "team")echo "checked"; ?>><label for="All">All</label>		
		
		</td></tr>
		<tr><td>
		<label for="roster_federation">Federations/Divisions</label><br />
		<input type="checkbox" name="showfed"<?php if ($showfed) echo ' checked'; ?>>Show Column
		</td><td>
		<?php efed_select_from_entries('roster_federation', 'feds', $fed, true, true); ?>            
		</td></tr>
		<tr><td>
		<label for="roster_weightclass">Weight Classes</label><br />
		<input type="checkbox" name="showwc"<?php if ($showwc) echo ' checked'; ?>>Show Column
		</td><td>
        <?php efed_select_from_entries('roster_weightclass', 'weightclasses', $wc, true); ?>
		</td></tr>
		<tr><td>
		<label for="roster_gender">Genders</label><br />
		<input type="checkbox" name="showgender"<?php if ($showgender) echo ' checked'; ?>>Show Column
		</td><td>
		<?php efed_select_from_entries('roster_gender', 'genders', $gender, true); ?>        
		</td></tr>
		<tr><td>
		<label for="roster_alignment">Alignments</label><br />
		<input type="checkbox" name="showalign"<?php if ($showalign) echo ' checked'; ?>>Show Column
		</td><td>
		<?php efed_select_from_entries('roster_alignment', 'alignments', $align, true); ?>
		</td></tr></table>
		
		<?php
	}	
	
	function save_roster(){
		global $post;
		$post_type = get_post_type($post);

		// If this isn't a 'roster' post, don't update it.
		if ( "roster" != $post_type ) return;
		if (count ($_POST) <= 0)
			return;


		update_post_meta($post->ID, "team", $_POST["roster_team"]);
		
		if (array_key_exists("roster_federation", $_POST))
			update_post_meta($post->ID, "fedfilter", $_POST["roster_federation"]);
		if (array_key_exists("roster_weightclass", $_POST))
			update_post_meta($post->ID, "wcfilter", $_POST["roster_weightclass"]);
		if (array_key_exists("roster_gender", $_POST))
			update_post_meta($post->ID, "genderfilter", $_POST["roster_gender"]);
		if (array_key_exists("roster_alignment", $_POST))
			update_post_meta($post->ID, "alignfilter", $_POST["roster_alignment"]);
		
		$fedtf = array_key_exists("showfed", $_POST);
		update_post_meta($post->ID, "showfed", $fedtf);
		
		$wctf = array_key_exists("showwc", $_POST);
		update_post_meta($post->ID, "showwc", $wctf);
		
		$gendertf = array_key_exists("showgender", $_POST);
		update_post_meta($post->ID, "showgender", $gendertf);
		
		$aligntf = array_key_exists("showalign", $_POST);
		update_post_meta($post->ID, "showalign", $aligntf);
	}
}
endif;