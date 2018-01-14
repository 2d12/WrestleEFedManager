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
		add_filter( 'wp_get_nav_menu_items', array($this, 'match_locations_filter'), 10, 3 );

	}
	
	function match_menu_children($parent_menu_ID, $parent_post_id, $ordermin)
	{
		$items = array();
		
		$childPosts = get_posts([
			'post_type'        	=> 'match',
			'posts_per_page'   	=> -1,
			'order_by' 			=> 'menu_order',
			'order' 			=> 'ASC',
			'post_parent'		=> $parent_post_id,
			]);
		//print_r($childPosts);
		
		foreach ($childPosts as $item)
		{		
			$items[] = (object)[
			'ID'                => $item->ID,
			'title'             => $item->post_title,
			'url'               => get_permalink($item),
			'menu_item_parent'  => $parent_menu_ID,
			'post_parent'       => $parentID,
			'menu_order'        => ++$ordermin,
			'db_id'             => $item->ID,
			'type'              => 'custom',
			'object'            => 'custom',
			'object_id'         => '',
			'classes'           => [],
			];
			
			$newitems = $this->match_menu_children($item->ID, $item->ID, $ordermin);
			if (count($newitems > 0))
			{
				$ordermin = $ordermin + count($newitems);
				$items = array_merge($items, $newitems) ;		
			}			
		}		
		return $items;
	}
	
	function match_locations_filter( $items, $menu, $args ) 
	{
		$customPostType = 'match';// Custom post type name		
		$ordermin = count($items) + 1;
		
		$customPosts = get_posts([
		'post_type'        	=> $customPostType,
		'posts_per_page'   	=> -1,
		'order_by' 			=> 'menu_order',
		'order' 			=> 'ASC',
		]);
		
		foreach ($items as $menuitem)
		{
			foreach ($customPosts as $match)
			{
				if ($menuitem->object_id == $match->ID)
				{	
					// This menu item IS a match entry.  Populate it with it's children.
					$newitems = $this->match_menu_children($menuitem->db_id, $match->ID, $ordermin);
					$ordermin = $ordermin + count($newitems);
					$items = array_merge($items, $newitems) ;
				}
			}
		}
		return $items;
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
        //'taxonomies'          => array( 'weightclass', 'division', 'gender', 'title' ),
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
		'menu_icon'   		   => 'dashicons-desktop',
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
		update_post_meta($post->ID, "title", $_POST["match_title"]);
		update_post_meta($post->ID, "titleupdate", $_POST["match_title_result"]);
	}
	
	function initialize_match_page_type() {
		add_meta_box("competitors", "Competitors", array( $this, 'match_competitors'), "match", "normal", "low");		
		add_meta_box("results", "Results", array( $this, 'match_results'), "match", "normal", "low");
		add_meta_box("championships", "Title Updates", array($this, 'match_titles'), "match", "normal", "low");
		add_meta_box("referee", "Referee", array( $this, 'match_referee'), "match", "side", "low");
		add_meta_box("rating", "Rating", array( $this, 'match_rating'), "match", "side", "low");
		
		add_meta_box("weightclass", "Weight Class", array( $this, 'match_weightclass'), "match", "side", "low");
		add_meta_box("gender", "Gender", array( $this, 'match_gender'), "match", "side", "low");
		add_meta_box("division", "Company/Division", array( $this, 'match_division'), "match", "side", "low");

	}	
	
	function match_titles()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$belts = $custom["title"][0];
		$result = $custom["titleupdate"][0];
		?>
		<table>
		<tr><th>Title</th><th>Result</th></tr>
		<tr><td> <?php
			efed_select_from_entries('match_title', 'championship', $belts); ?>
		</td><td>
			<select name='match_title_result'>
				<option>&nbsp;</option>
				<option value="defense" <?php if ($result == "defense") echo 'selected' ?>>Successful Defense</option>
				<option value="newchamp" <?php if ($result == "newchamp") echo 'selected' ?>>New Champion</option>
				<option value="vacate" <?php if ($result == "vacate") echo 'selected' ?>>Vacated</option>
			</select>
		</td>
		</tr>
		</table>
		<?php
	}
	
	function match_weightclass()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$wc = $custom["weightclass"][0];
		//echo 'Saved value : ' . $wc . '<br />';
		efed_select_from_entries('match_weightclass', 'weightclasses', $wc);
	}
	function match_gender()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$gender = $custom["gender"][0];
		efed_select_from_entries('match_gender', 'genders', $gender);
	}
	function match_division()
	{
		global $post;
		$custom = get_post_custom($post->ID);
		$div = $custom["division"][0];
		efed_select_from_entries('match_division', 'feds', $div, true, true);
	}
	
	function match_competitors() {
		global $post;
		$custom = get_post_custom($post->ID);
		$competitors = unserialize($custom["competitors"][0]);
		efed_select_from_entries('match_competitors', 'workers', $competitors, true);
	}
	
	function match_results() {
		global $post;
		$custom = get_post_custom($post->ID);
		$victors = unserialize($custom["victors"][0]);
		$time = $custom["time"][0];
		$finisher = $custom["finisher"][0];
		?>
		<table>
		<tr><td><label>Victor(s):</label></td><td><?php efed_select_from_entries('match_victors', 'workers', $victors, true);?></td></tr>
		<tr><td><label>Time:</label></td><td><input name="match_time" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $time; ?>" /></td></tr>
		<tr><td><label>Finish:</label></td><td><input name="match_finisher" type="text" style="width:100%;box-sizing:border-box;" value="<?php echo $finisher; ?>" /></td></tr></table>
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