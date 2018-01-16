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
 * @package           EFedmanager
 *
 * @wordpress-plugin
 * Plugin Name:       eEFed Manager
 * Plugin URI:        https://github.com/2d12/WrestleEFedManager
 * Description:       Establishes a usable history for a wrestling or MMA e-fed, including the ability to store multiple federations,
 *                    workers, titles (with histories), events, and match history.
 * Version:           1.0.0
 * Author:            E. Steev Ramsdell
 * Author URI:        http://www.2d12.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       efedmanager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-efedmanager-activator.php
 */
function activate_efedmanager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-efedmanager-activator.php';
	efedmanager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-efedmanager-deactivator.php
 */
function deactivate_efedmanager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-efedmanager-deactivator.php';
	efedmanager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_efedmanager' );
register_deactivation_hook( __FILE__, 'deactivate_efedmanager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-efedmanager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_efedmanager() {

	$plugin = new efedmanager();
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
		$string = "";
		$args = array(
			'post_type' => 'match',
			'order_by' => 'parent menu_order',
			'order' => 'ASC',
			'post_parent' => $post->ID,
			'post_status' => 'publish',
			'posts_per_page' => -1,			
		);
		if (is_singular('match') ) {		
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
			'post_type' => 'match',
			'order_by' => 'menu_order',
			'order' => 'ASC',
			'post_parent' => $post->post_parent,
			'post_status' => 'publish',
			'posts_per_page' => -1,			
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
			'post_type' => 'match',
			'order_by' => 'menu_order',
			'order' => 'ASC',
			'post_parent' => $post->post_parent,
			'post_status' => 'publish',
			'posts_per_page' => -1,			
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
			'post_type' => $postType,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_status' => 'publish',
			'posts_per_page' => -1,	
		);
		
		if ($use_hierarchy)
		{
				$args['post_parent'] = 0;
		}
		
		
		$lastposts = get_posts($args);
		$retarr = array();
		$multisize = floor(count($lastposts) / 3);
		$multisize = max(4,$multisize);
		$multisize = min(12, $multisize);
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
				echo '[]" multiple size="' . $multisize . '"';
			}
			else
			{
				echo '"';
			}
			echo ' style="width:100%;box-sizing:border-box;">';
			if (!$multiple) echo '<option value="-1">&nbsp;</option>';
			efed_get_options($postType, $retarr, $selected, $use_hierarchy);
			
			echo '</select>';
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
			'post_type' => array(),
			'order_by' => 'title',
			'order' => 'ASC',
			'post_status' => 'publish',
			'posts_per_page' => -1,		
			'meta_query' => array()			
		);
	if ($teamfilter=="individual" || $teamfilter == "" || $teamfilter == "all")
	{
		$args['post_type'][] = 'workers';
	}
	if ($teamfilter=="team" || $teamfilter == "" || $teamfilter == "all")
	{
		$args['post_type'][] = 'teams';
	}
	
	if (count($weightfilter[0]) > 0 )
	{
		$args['meta_query'][] = array(
			'key' => 'weightclass',
			'value' => $weightfilter[0],
			'compare' => 'IN',
		);
	}
	
	if (count($genderfilter[0]) > 0 )
	{
		$args['meta_query'][] = array(
			'key' => 'gender',
			'value' => $genderfilter[0],
			'compare' => 'IN',
		);
	}
	
	if (count($alignfilter[0]) > 0 )
	{
		$args['meta_query'][] = array(
			'relation' => 'OR', // Optional, defaults to "AND"
			array(
				'key'     => 'alignment',
				'value'   => $alignFilter[0],
				'compare' => 'IN',
			),
			array(
				'key'     => '2alignment',
				'value'   => $alignFilter[0],
				'compare' => 'IN',
			),
		);
	}
	
	$lastposts = get_posts($args);
	$retarr = array();
		
		foreach($lastposts as $thispost)
		{
			if (count($divfilter[0] > 0))
			{
				$postfeds = get_post_meta($thispost->ID, 'federation');			
				if (count($postfeds) > 0 && array_key_exists(0, $postfeds) && is_array($postfeds[0]) )
				{
					foreach ($postfeds[0] as $postfed)
					{
						foreach ($divfilter[0] as $filterfed)
						{
							if ($postfed == $filterfed)
							{
							if (get_post_type($thispost->ID) == 'workers')						
							{
								$retarr[] = array(
								'title' => $thispost->post_title, 
								'id' => $thispost->ID,
								'federation' => get_post_meta($thispost->ID, 'federation'),
								'weightclass' => get_post_meta($thispost->ID, 'weightclass', true),
								'gender' => get_post_meta($thispost->ID, 'gender', true),
								'alignment' => get_post_meta($thispost->ID, 'walignment', true),
								);
							}
							else
							{
								$retarr[] = array(
								'title' => $thispost->post_title, 
								'id' => $thispost->ID,
								'federation' => get_post_meta($thispost->ID, 'federation'),
								'weightclass' => get_post_meta($thispost->ID, 'weightclass', true),
								'gender' => get_post_meta($thispost->ID, 'gender', true),
								'alignment' => get_post_meta($thispost->ID, 'alignment', true),
								);
							}
							break 2;
							}
						}
					}
				}
			}
			else
			{
				if (get_post_type($thispost->ID) == 'workers')						
				{
					$retarr[] = array(
					'title' => $thispost->post_title, 
					'id' => $thispost->ID,
					'federation' => get_post_meta($thispost->ID, 'federation'),
					'weightclass' => get_post_meta($thispost->ID, 'weightclass', true),
					'gender' => get_post_meta($thispost->ID, 'gender', true),
					'alignment' => get_post_meta($thispost->ID, 'walignment', true),
					);
				}
				else
				{
					$retarr[] = array(
					'title' => $thispost->post_title, 
					'id' => $thispost->ID,
					'federation' => get_post_meta($thispost->ID, 'federation'),
					'weightclass' => get_post_meta($thispost->ID, 'weightclass', true),
					'gender' => get_post_meta($thispost->ID, 'gender', true),
					'alignment' => get_post_meta($thispost->ID, 'alignment', true),
					);
				}
			}
		}
	return $retarr;
}

function efed_get_all_worker_ids($workerID)
{
	$args = array(
		'post_type' => 'teams',
		'post_status' => 'publish',
		'posts_per_page' => -1,		
		);
		
	$allteams = get_posts($args);
	$rv = array( $workerID, ); // Start with myself
	foreach ($allteams as $team)
	{
		$tc = get_post_meta($team->ID, 'team_competitors', true);
		foreach ($tc as $tm)
		{
			if ($tm == $workerID)
			{
				$rv[] = $team->ID; // Add teams I am a competitor on.
				break;
			}
		}
	}

	return $rv;
	
	}

function efed_worker_match_history($workerID)
{
	$myids = efed_get_all_worker_ids($workerID);
	
	$args = array(
			'post_type' => 'match',
			'order_by' => 'date',
			'order' => 'DESC',
			'post_status' => 'publish',
			'posts_per_page' => -1,		
		);
	$out = get_posts($args);
	$rv = array();
	foreach ($out as $match)
	{	
		$competitors = get_post_meta($match->ID, 'competitors', false);
		$victors = get_post_meta($match->ID, 'victors', false);
	
		foreach ($myids as $anID)
		{
			if ($competitors[0] != null && count($competitors[0]) > 0 && in_array($anID, $competitors[0]))
			{
				if (in_array($anID, $victors[0]))
				{
					$rv[$match->ID] = 'WIN';
				}
				else if (count($victors[0]) == 0)
				{
					$rv[$match->ID] = 'DRAW';
				}
				else
				{
					$rv[$match->ID] = 'LOSS';
				}
				break;
			}
		}
	}
	
	return $rv;
}

function efed_worker_title_history($workerID)
{
	$myids = efed_get_all_worker_ids($workerID);
	
	$args = array(
		'post_type' => 'match',
		'order_by' => 'date',
		'order' => 'ASC',
		'post_status' => 'publish',
		'posts_per_page' => -1,		
	);
	
	$args['meta_query'][] = array(
		'key' => 'titleupdate',
		'value' => array ('newchamp', 'vacate'),
		'compare' => 'IN',
	);
	
	$titleChanges = get_posts($args);
	
	$champList = array(0=>'');
	$lastChamp = array();
	$workerHistory = array();
	
	foreach ($titleChanges as $change)
	{
		$victor = get_post_meta($change->ID, 'victors')[0];
		//echo '<pre>VICTOR LIST:';
		//print_r($victor);
		//echo '</pre>';
		//echo 'Is ' . $workerID . " in array?  ";
		if (is_array($victor))
			$workerIsVictor = (count(array_intersect($myids, $victor)) > 0);
		else
			$workerIsVictor = (in_array($victor, $myids));
		//echo $workerIsVictor?"YES<br />":"NO<br />";
		$victorNum = array_search($victor, $champList);
		if ($victorNum == false)
		{
			$victorNum = count($champList);
			$champList[$victorNum] = $victor;
		}
		
		$defenseType = get_post_meta($change->ID, 'titleupdate', true);
		$title = get_post_meta($change->ID, 'title', true);
		
		if (count($lastChamp) > 0 && array_key_exists($title, $lastChamp) && array_key_exists($lastChamp[$title], $champList))
			$workerIsLoser = (count(array_intersect($myids, $champList[$lastChamp[$title]])) > 0);
		else
			$workerIsLoser = false;
		
		
		if ($defenseType == 'newchamp')
		{
			//echo 'NEW CHAMP...Was it ME?<br />';
			// Did this worker win here?
			if ($workerIsVictor)
			{
				//echo 'YES IT WAS!<br />';
				if (array_key_exists($title, $workerHistory) == false)
				{
					$workerHistory[$title] = array();
				}
				if (array_key_exists('reigns', $workerHistory[$title]) == false)
				{
					$workerHistory[$title]['reigns'] = array();
				}
				
				if (array_key_exists($title, $lastChamp) && array_key_exists($lastChamp[$title], $champList))
					$prev = $champList[$lastChamp[$title]];
				else
					$prev = array();
				
				$workerHistory[$title]['reigns'][] = array(
					'win' => get_the_date( 'Y-m-d', $change->ID ),
					'prev' => $prev,
					'next' => null,
					'lost' => null,
					'cowinner' => array(),
				);			
				if (array_key_exists('count', $workerHistory[$title]))
				{
					$workerHistory[$title]['count'] = $workerHistory[$title]['count'] + 1;
				}
				else
				{
					$workerHistory[$title]['count'] = 1;							
				}
				$count = $workerHistory[$title]['count'];
				foreach ($victor as $vid)
				{
					if (get_post_type($workerID) == 'teams')
					{
						if ($vid == $workerID)
							continue;
						else
							$workerHistory[$title]['reigns'][$count - 1]['cowinner'][] = $vid;
					}
					else
					{
						if ($vid == $workerID)
							continue;
						else if (get_post_type($vid) == 'teams')
						{
							$teammates = get_post_meta($vid, 'team_competitors', true);
							foreach ($teammates as $member)
							{
								if ($member == $workerID)
								{
									continue;
								}
								else
								{
									$workerHistory[$title]['reigns'][$count - 1]['cowinner'][] = $member;
								}
							}
						}
						else
						{
							$workerHistory[$title]['reigns'][$count - 1]['cowinner'][] = $vid;
						}
					}
				}
			}
			// Did this worker LOSE here?				
			else if ($workerIsLoser)
			{
				//echo 'NO, DUMBASS, I LOST IT HERE!<br />';
				$reignNum = $workerHistory[$title]['count'];
				$workerHistory[$title]['reigns'][$reignNum - 1]['next'] = $victor;
				$workerHistory[$title]['reigns'][$reignNum - 1]['lost'] = get_the_date( 'Y-m-d', $change->ID );
				if (array_key_exists('days', $workerHistory[$title]) == false)
				{
					//echo 'Setting total reign days to 0';
					$workerHistory[$title]['days'] = 0;
				}
				$rstart = strtotime($workerHistory[$title]['reigns'][$reignNum - 1]['win']);
				$rend = strtotime($workerHistory[$title]['reigns'][$reignNum - 1]['lost']);
				$rdiff = floor(($rend - $rstart)/(60 * 60 * 24));
				//echo 'Setting total reign days to ' . ($workerHistory[$title]['days'] + $rdiff);
				$workerHistory[$title]['days'] = $workerHistory[$title]['days'] + $rdiff;
			}				

			// else this worker wasn't involved - do nothing.
			
			$lastChamp[$title] = $victorNum;
		}
		else if ($defenseType == 'vacate')
		{
			//echo 'Title was vacated...<br />';
			if ($workerIsLoser)
			{
				$reignNum = $workerHistory[$title]['count'];
				$workerHistory[$title]['reigns'][$reignNum - 1]['next'] = -1;
				$workerHistory[$title]['reigns'][$reignNum - 1]['lost'] = get_the_date( 'Y-m-d', $change->ID );
				if (array_key_exists('days', $workerHistory[$title]) == false)
				{
					//echo 'Setting total reign days to 0';
					$workerHistory[$title]['days'] = 0;
				}
				$rstart = strtotime($workerHistory[$title]['reigns'][$reignNum - 1]['win']);
				$rend = strtotime($workerHistory[$title]['reigns'][$reignNum - 1]['lost']);
				$rdiff = floor(($rend - $rstart)/(60 * 60 * 24));
				//echo 'Setting total reign days to ' . ($workerHistory[$title]['days'] + $rdiff);
				$workerHistory[$title]['days'] = $workerHistory[$title]['days'] + $rdiff;
			}
			
			$lastChamp[$title] = -1;
		}
	}
	
	//echo '<pre>';
	//print_r($workerHistory);
	//echo '</pre>';
	foreach ($workerHistory as $key => $titleHistory)
	{
		$reignCount = $titleHistory['count'];
		if ($titleHistory['reigns'][$reignCount -1]['lost'] == null)
		{
			//echo 'COUNTING DAYS AS REIGNING CHAMPION<br />';
			if (array_key_exists('days', $titleHistory) == false)
				{
					//echo 'Setting total reign days to 0';
					$titleHistory['days'] = 0;
				}
				$rstart = strtotime($titleHistory['reigns'][$reignCount - 1]['win']);
				$rend = time();
				$rdiff = floor(($rend - $rstart)/(60 * 60 * 24));
				//echo 'Setting total reign days to ' . ($workerHistory[$title]['days'] + $rdiff);
				$titleHistory['days'] = $titleHistory['days'] + $rdiff;
		}
	$workerHistory[$key] = $titleHistory;
	}
	//echo '<pre>';
	//print_r($workerHistory);
	//echo '</pre>';
	return $workerHistory;
}

function efed_title_history($titleID)
{
	$args = array(
		'post_type' => 'match',
		'order_by' => 'date',
		'order' => 'ASC',
		'post_status' => 'publish',
		'posts_per_page' => -1,		
		'meta_query' => array(),
		);
	$args['meta_query'][] = array(
			'key' => 'title',
			'value' => $titleID,
		);
	
	$out = get_posts($args);
	$reigns = array();
	$reignCount = array();
	$champList = array(0=>''); // Get rid of false failures in array_search
	
	$lastID = -1;
	foreach ($out as $titleMatch)
	{
		$defenseType = get_post_meta($titleMatch->ID, 'titleupdate', true);
		$victor = get_post_meta($titleMatch->ID, 'victors')[0];
		if ($defenseType == "newchamp" )
		{
			$victorNum = array_search($victor, $champList);
			if ($victorNum == false)
			{
				$victorNum = count($champList);
				$champList[$victorNum] = $victor;
			}
			
			if ($lastID >= 0)
			{
				$loserNum = array_search($reigns[$lastID]['champion'], $champList);

				$reigns[$lastID]['loss'] = get_the_date( 'Y-m-d', $titleMatch->ID );
				$rend = strtotime(get_the_date( 'Y-m-d', $titleMatch->ID ));
				$rstart = strtotime($reigns[$lastID]['win']);
				$rdiff = floor(($rend - $rstart) / (60 * 60 * 24));
				$reigns[$lastID]['length'] = $rdiff;
				if ($loserNum != false)
				{
					if (array_key_exists($loserNum, $reignCount) && array_key_exists ('days', $reignCount[$loserNum]))
					{	
						$reignCount[$loserNum]['days'] = $reignCount[$loserNum]['days'] + $rdiff;
					}
					else
					{
						$reignCount[$loserNum]['days'] = $rdiff;
					}
					if ($reigns[$lastID]['champion'] != "Title Vacant")
						$reigns[$lastID]['total'] = $reignCount[$loserNum]['days'];
				}
			}
			
			if (array_key_exists($victorNum, $reignCount))
			{
				$reignCount[$victorNum]['count'] = $reignCount[$victorNum]['count'] + 1;
			}
			else
			{
				$reignCount[$victorNum]['count'] = 1;
			}
			
			$reigns[++$lastID] = array(
				'champion' => $victor,
				'win' => get_the_date( 'Y-m-d', $titleMatch->ID ),
				'reignNum' => $reignCount[$victorNum]['count'],
				'defenses' => 0,
				'loss' => null,
			);
		}
		else if ($defenseType == "defense" )
		{
			$reigns[$lastID]['defenses'] = $reigns[$lastID]['defenses'] + 1;
		}
		else // VACATED
		{
			$victorNum = array_search($victor, $champList);
			
			if ($lastID >= 0)
			{
				$reigns[$lastID]['loss'] = get_the_date( 'Y-m-d', $titleMatch->ID );

				$rend = strtotime(get_the_date( 'Y-m-d', $titleMatch->ID ));
				$rstart = strtotime($reigns[$lastID]['win']);
				$rdiff = floor(($rend - $rstart) / (60 * 60 * 24));
				$reigns[$lastID]['length'] = $rdiff;
				if (array_key_exists($victorNum, $reignCount) && array_key_exists('days',$reignCount[$victorNum]))
					$reignCount[$victorNum]['days'] = $reignCount[$victorNum]['days'] + $rdiff;
				else
					$reignCount[$victorNum]['days'] = $rdiff;
				$reigns[$lastID]['total'] = $reignCount[$victorNum]['days'];
			}
			
			$reigns[++$lastID] = array(
				'champion' => "Title Vacant",
				'win' => get_the_date( 'Y-m-d', $titleMatch->ID ),
				'reignNum' => "N/A",
				'defenses' => "N/A",
				'loss' => null,
				'total' => "N/A",
				);
			
		}
		
	}
	
	$rend = time();
	$rstart = strtotime($reigns[$lastID]['win']);
	$rdiff = floor(($rend - $rstart) / (60 * 60 * 24));
	$reigns[$lastID]['length'] = $rdiff;
	if (array_key_exists($victorNum, $reignCount) && array_key_exists('days', $reignCount[$victorNum]))
		$reignCount[$victorNum]['days'] += $rdiff;
	else
		$reignCount[$victorNum]['days'] = $rdiff;
	if ($reigns[$lastID]['champion'] != "Title Vacant")
		$reigns[$lastID]['total'] = $reignCount[$victorNum]['days'];
	
	//print_r($reignCount);
/* 	echo '<pre>';
	print_r ($reigns);
	echo '</pre>'; */
	
	return $reigns;	
}

	
run_efedmanager();
