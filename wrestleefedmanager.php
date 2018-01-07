<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/2d12/WrestleEFedManager
 * @since             1.0.0
 * @package           Wrestleefedmanager
 *
 * @wordpress-plugin
 * Plugin Name:       WrestleEFed Manager
 * Plugin URI:        https://github.com/2d12/WrestleEFedManager
 * Description:       Establishes a usable history for a wrestling e-fed, including the ability to store multiple federations,
 *                    workers, titles (with histories), events, and match history.
 * Version:           1.0.0
 * Author:            E. Steev Ramsdell
 * Author URI:        http://www.2d12.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wrestleefedmanager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wrestleefedmanager-activator.php
 */
function activate_wrestleefedmanager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wrestleefedmanager-activator.php';
	Wrestleefedmanager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wrestleefedmanager-deactivator.php
 */
function deactivate_wrestleefedmanager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wrestleefedmanager-deactivator.php';
	Wrestleefedmanager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wrestleefedmanager' );
register_deactivation_hook( __FILE__, 'deactivate_wrestleefedmanager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wrestleefedmanager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wrestleefedmanager() {

	$plugin = new Wrestleefedmanager();
	$plugin->run();
}

 function efed_list_posts($args)
	{
        $lastposts = get_posts($args);
		$string = "";
        foreach($lastposts as $thispost)
		{
			setup_postdata($thispost);

			$string = $string . "<li";
			if ( $thispost->ID == $wp_query->post->ID ) 
				{
					$string = $string . " class=\"current\""; 
				}
			
			$string = $string . ">";
			$string = $string . "<a href=";
			$string = $string . "\"" . get_permalink($thispost->ID) . "\">" . get_the_title($thispost->ID) . "</a>";
			$string = $string . "</li>";    
		}
		
		return $string;
	}

 function efed_list_child_matches() 
		{ 
		wp_reset_postdata();
		global $post; 		 
		$args = array(
			post_type => 'match',
			order_by => 'parent menu_order',
			order => 'ASC',
			post_parent => $post->ID,
			post_status => 'publish',
			posts_per_page => -1,			
		);
		if (is_singular('match') ) {
		//if ( is_page() && $post->post_parent )	
		// wp_list_pages() 
			//$lastposts = get_posts('sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0');			
			$childpages = efed_list_posts( $args );
			if ($childpages)
			{
				$string = '<ul>' . $childpages . '</ul>';
			}
		}
		wp_reset_postdata();
		return $string;
		}
		
function efed_previous_match($show_title = false)
	{
		wp_reset_postdata();
		global $post; 		 
		$args = array(
			post_type => 'match',
			order_by => 'menu_order',
			order => 'ASC',
			post_parent => $post->post_parent,
			post_status => 'publish',
			posts_per_page => -1,			
		);
		
		$originalID = $post->ID;
		
		$lastposts = get_posts($args);
		$string = "";
		foreach($lastposts as $thispost)
		{

			setup_postdata($thispost);
			if ( $thispost->ID == $originalID ) 
				{
					 break;
				}	
			if ($show_title)
			{
				$title = "&laquo; " . $thispost->title;
			}
			else
			{
				$title = "&laquo; Previous";
			}
			$string = " <a href=\"" . get_permalink($thispost->ID) . "\">" . $title . "</a>";
		}
		wp_reset_postdata();
		return $string;
	}
	
function efed_next_match($show_title = false)
	{
		wp_reset_postdata();
		global $post; 		 
		$args = array(
			post_type => 'match',
			order_by => 'menu_order',
			order => 'ASC',
			post_parent => $post->post_parent,
			post_status => 'publish',
			posts_per_page => -1,			
		);
		$lastposts = get_posts($args);
		$string = "";
		$nextpost = false;
        foreach($lastposts as $thispost)
		{
			setup_postdata($thispost);
			if ($show_title)
			{
				$title = $thispost->title . "&raquo;";
			}
			else
			{
				$title = "Next &raquo;";
			}
			if ($nextpost)
			{
				$string = "<a href=\"" . get_permalink($thispost->ID) . "\">" . $title . "</a>";
				break;
			}
			else
			{
				if ( $thispost->ID == $post->ID ) 
					{
						 $nextpost = true;
					}
			}
		}
		wp_reset_postdata();
		return  $string;
	}
	
function efed_select_from_entries($varname, $postType, $selected, $multiple=false, $use_hierarchy=false)
{	
	$args = array(
			post_type => $postType,
			orderby => 'title',
			order => 'ASC',
			post_status => 'publish',
			posts_per_page => -1,	
		);
		
		if ($use_hierarchy)
		{
				$args[post_parent] = 0;
		}
		
		
		$lastposts = get_posts($args);
		$retarr = array();
		foreach($lastposts as $thispost)
		{
			$retarr[] = array(
				'title' => $thispost->post_title, 
				'id' => $thispost->ID,
				);
		}
		if (count($retarr) > 0)
		{	
			echo '<select name="' . $varname;
			if ($multiple)
			{
				echo '[]" multiple';
			}
			else
			{
				echo '"';
			}
			echo ' style="width:100%;box-sizing:border-box;">';
			if (!$multiple) echo '<option value="">&nbsp;</option>';
			efed_get_options($postType, $retarr, $selected, $use_hierarchy);
			/*foreach ($retarr as $entry)
			{
				echo '<option value="' . $entry['id'] . '"';
				if ($entry['id'] == $selected || (is_array($selected) && in_array($entry['id'], $selected)))
				{
					echo ' selected';
				}
				
				echo '>';
			    echo $entry['title'] . '</option>';
				
				if ($use_hierarchy)
				{
					
				}
			}*/
			
			echo '</select>';
			//echo $SelectedValuesToOutput;
		}
		else
		{
			echo 'No options to choose.<br />';
		}
}

function efed_get_options($postType, $option_array, $selected, $hierarchy, $level = 1)
{
	foreach ($option_array as $entry)
		{
			echo '<option value="' . $entry['id'] . '"';
			if ($entry['id'] == $selected || (is_array($selected) && in_array($entry['id'], $selected)))
			{
				echo ' selected';
			}
			
			echo '>';
			echo $entry['title'] . '</option>';
			
			if ($hierarchy)
			{
				$args = array(
					post_type => $postType,
					orderby => 'title',
					order => 'ASC',
					post_status => 'publish',
					posts_per_page => -1,	
					post_parent => $entry['id'],
				);
				$lastposts = get_posts($args);
				
				$retarr = array();
				$prefix = '';
				for ($i = 1; $i <= $level; $i++)
				{
					$prefix = $prefix . "&mdash;";
				}
				foreach($lastposts as $thispost)
				{
					$retarr[] = array(
						'title' => $prefix . $thispost->post_title, 
						'id' => $thispost->ID,
						);
				}
			
			if (count($retarr) > 0)
				{
					efed_get_options($postType, $retarr, $selected, $hierarchy, $level + 1);
				}
			}
		}
}

function efed_populate_roster($teamfilter, $divfilter, $weightfilter, $genderfilter, $alignfilter)
{
	
	$args = array(
			post_type => array(),
			order_by => 'title',
			order => 'ASC',
			post_status => 'publish',
			posts_per_page => -1,		
			meta_query => array()			
		);
	if ($teamfilter=="individual" || $teamfilter == "" || $teamfilter == "all")
	{
		$args[post_type][] = 'workers';
	}
	if ($teamfilter=="team" || $teamfilter == "" || $teamfilter == "all")
	{
		$args[post_type][] = 'teams';
	}
	
	/*if (count($divfilter[0]) > 0 )
	{
		$args[meta_query][] = array(
			'key' => 'federation',
			'value' => $divfilter[0],
			'compare' => 'IN',
		);
	}*/
	
	if (count($weightfilter[0]) > 0 )
	{
		$args[meta_query][] = array(
			'key' => 'weightclass',
			'value' => $weightfilter[0],
			'compare' => 'IN',
		);
	}
	
	if (count($genderfilter[0]) > 0 )
	{
		$args[meta_query][] = array(
			'key' => 'gender',
			'value' => $genderfilter[0],
			'compare' => 'IN',
		);
	}
	
	if (count($alignfilter[0]) > 0 )
	{
		$args[meta_query][] = array(
			'key' => 'alignment',
			'value' => $alignfilter[0],
			'compare' => 'IN',
		);
	}
	
	$lastposts = get_posts($args);
	$retarr = array();
		
		foreach($lastposts as $thispost)
		{
			if (count($divfilter[0] > 0))
			{
				$postfeds = get_post_meta($thispost->ID, 'federation');			
				foreach ($postfeds[0] as $postfed)
				{
					foreach ($divfilter[0] as $filterfed)
					{
						if ($postfed == $filterfed)
						{
						$retarr[] = array(
							'title' => $thispost->post_title, 
							'id' => $thispost->ID,
							'federation' => get_post_meta($thispost->ID, 'federation'),
							'weightclass' => get_post_meta($thispost->ID, 'weightclass', true),
							'gender' => get_post_meta($thispost->ID, 'gender', true),
							'alignment' => get_post_meta($thispost->ID, 'alignment', true),
							);
						break 2;
						}
					}
				}
			}
			else
			{
				$retarr[] = array(
					'title' => $thispost->post_title, 
					'id' => $thispost->ID,
					'federation' => get_post_meta($thispost->ID, 'federation', false),
					'weightclass' => get_post_meta($thispost->ID, 'weightclass', true),
					'gender' => get_post_meta($thispost->ID, 'gender', true),
					'alignment' => get_post_meta($thispost->ID, 'alignment', true),
					);
			}
		}
	//echo '<pre>';
	//print_r($retarr);
	//echo '</pre>';
	
	
	return $retarr;
	
	//print_r($args);
	//echo '<br />----------<br />';

}

function efed_get_all_federations()
{
		//wp_reset_postdata();
		//global $post; 		 
		$args = array(
			post_type => 'feds',
			order_by => 'title',
			order => 'ASC',
			post_status => 'publish',
			posts_per_page => -1,			
		);
		$lastposts = get_posts($args);
		//return array(array('title'=>"Fake World Wrestling Entertainment",'abbr'=>"WWE"),
		//			array('title'=>count($lastposts), 'abbr'=>"PWA"));
		$retarr = array();
		//echo 'POSTCOUNT:' . count($lastposts) . '<br />';
		foreach($lastposts as $thispost)
		{
			//echo $thispost->title . " -- " ; //$thispost->get_post_meta($thispost->ID, 'abbr', true) . '<br />';
			$retarr[] = array(
				'title' => $thispost->post_title, 
				'id' => $thispost->ID,//$thispost->get_post_meta($thispost->ID, 'abbr', true),
				);
				
			$index++;
		}
		//echo 'FEDCOUNT:' . count($retarr) . '<br />';
		//wp_reset_postdata();
		return $retarr;
}

function efed_get_all_taxonomy_values($taxonomy)
{
/*	$args=array(
		'public'   => true,
		'_builtin' => false
		);
	$output = 'names';
	$operator = 'and';
	$taxonomies=get_taxonomies($args,$output,$operator); 
	if  ($taxonomies) 
	{
		foreach ($taxonomies  as $tax ) 
		{*/
			$retarr = array();
			$terms = get_terms([
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
				]);
			
			$terms = get_terms($tax);
			foreach ( $terms as $term) 
			{
				$retarr[] = array(
					'title' => $term->name,
					'ID' => $term->name,
					);
			}
			
			return $retarr;
		//}
	//}
}


	
run_wrestleefedmanager();
