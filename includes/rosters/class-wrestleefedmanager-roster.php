<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Wrestleefedmanager_Roster' ) ) :

class Wrestleefedmanager_Roster {
	
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
        'menu_position'       => 17,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
		'register_meta_box_cb' => array( $this, 'initialize_roster_post_type'),
    );
	 
		register_post_type( 'roster', $rosterargs);
	}
	
	
	function initialize_roster_post_type() {
		/*
		add_meta_box( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, 
		              string $context = 'advanced', string $priority = 'default', array $callback_args = null )*/
		add_meta_box("filters", "Filters", array( $this, 'roster_filters'), "roster", "normal", "low");
	
		}

    /**
     *
     */
    function roster_filters() {
		global $post;
		$custom = get_post_custom($post->ID);
		$team = $custom["team"][0];
		$fed = unserialize($custom["federations"][0]);
		$wc = unserialize($custom["weightclasses"][0]);
		$gender = unserialize($custom["genders"][0]);
		$align = unserialize($custom["alignments"][0]);
		$showfed = $custom["showfed"][0];
		$showwc = $custom["showwc"][0];
		$showdiv = $custom["showdiv"][0];
		$showgender = $custom["showgender"][0];
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
		<input type="checkbox" name="showfed" <?php if ($showfed) echo ' checked'; ?> >Show Column
		</td><td>
		<?php efed_select_from_entries('roster_federation', 'feds', $fed, true, true); ?>            
		</td></tr>
		<tr><td>
		<label for="roster_weightclass">Weight Classes</label><br />
		<input type="checkbox" name="showwc" <?php if ($showwc) echo ' checked'; ?> >Show Column
		</td><td>
        <?php efed_select_from_entries('roster_weightclass', 'weightclasses', $wc, true); ?>
		</td></tr>
		<tr><td>
		<label for="roster_gender">Genders</label><br />
		<input type="checkbox" name="showgender" <?php if ($showgender) echo ' checked'; ?> >Show Column
		</td><td>
		<?php efed_select_from_entries('roster_gender', 'genders', $gender, true); ?>        
		</td></tr>
		<tr><td>
		<label for="roster_alignment">Alignments</label><br />
		<input type="checkbox" name="showalign" <?php if ($showalign) echo ' checked'; ?> >Show Column
		</td><td>
		<?php efed_select_from_entries('roster_alignment', 'alignments', $align, true); ?>
		</td></tr></table>
		
		<?php
	}
	
	
	function save_roster(){
		global $post;
		
		update_post_meta($post->ID, "team", $_POST["roster_team"]);
		
		update_post_meta($post->ID, "federations", $_POST["roster_federation"]);
		update_post_meta($post->ID, "weightclasses", $_POST["roster_weightclass"]);
		update_post_meta($post->ID, "genders", $_POST["roster_gender"]);
		update_post_meta($post->ID, "alignments", $_POST["roster_alignment"]);
		
		$fedtf = $_POST['showfed'] ? true : false;
		update_post_meta($post->ID, "showfed", $fedtf);
		
		$wctf = $_POST['showwc'] ? true : false;
		update_post_meta($post->ID, "showwc", $wctf);
		
		$divtf = $_POST['showdiv'] ? true : false;
		update_post_meta($post->ID, "showdiv", $divtf);
		
		$gendertf = $_POST['showgender'] ? true : false;
		update_post_meta($post->ID, "showgender", $gendertf);
		
		$aligntf = $_POST['showalign'] ? true : false;
		update_post_meta($post->ID, "showalign", $aligntf);
		
		
//		update_post_meta($post->ID, "aka", $_POST["worker_aka"]);
	}
	
	/*
	<?php the_terms( $post->ID, 'weightclass', 'Weight Class: ', ', ', ' ' ); ?>
	*/
}
endif;