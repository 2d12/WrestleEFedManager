<?php
 /*Template Name: Roster
 */
 
get_header(); ?>

<div id="primary" class="content-area content-area-no-sidebar">
    <div id="content" role="main"> 
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( ! is_single() ) { ?><a href="<?php echo esc_url( get_permalink() ); ?>"><?php } ?>

			<div class="single-entry">
				<header class="entry-header">
				   <h1 class="entry-title single-entry-title" style="color:black;"><?php the_title(); ?></h1>
				</header>
				
				<!-- Display contents -->
				<div class="entry-content">
					<table border="1">
					<tr><th style="font-weight:bold;">
					<?php
					$entrytype = get_post_meta(get_the_ID(), 'team', true);
					$showfed = get_post_meta(get_the_ID(), 'showfed', true);
					$showwc = get_post_meta(get_the_ID(), 'showwc', true);
					$showdiv = get_post_meta(get_the_ID(), 'showdiv', true);
					$showgender = get_post_meta(get_the_ID(), 'showgender', true);
					$showalign = get_post_meta(get_the_ID(), 'showalign', true);
					
					$returnvals = efed_populate_roster($entrytype, get_post_meta(get_the_ID(), 'fedfilter', false ), get_post_meta(get_the_ID(), 'wcfilter', false ), get_post_meta(get_the_ID(), 'genderfilter', false ), get_post_meta(get_the_ID(), 'alignfilter', false ));
					
					if ($entrytype == "individual")
					{
						echo 'Worker';
					}
					else if ($entrytype == "team")
					{
						echo 'Team';
					}
					else
					{
						echo 'Worker or Team';
					}
					?>
					</th>
					<?php 
					if ($showfed) {echo '<th style="font-weight:bold;">Federation</th>';} 
					if ($showwc) {echo '<th style="font-weight:bold;">Weight Class</th>';} 
					if ($showgender) {echo '<th style="font-weight:bold;">Gender</th>';} 
					if ($showalign) {echo '<th style="font-weight:bold;">Alignment</th>';} 
					?>
					</tr>
					
					<?php
						foreach ($returnvals as $row)
						{
							echo '<tr>';
							echo '<td><a href="' . get_permalink($row['id']) . '">' . $row['title'] . '</a></td>';
							if ($showfed) 
							{
								echo '<td>';
								foreach($row['federation'][0] as $fedentry)
									{
										echo get_the_title($fedentry) . '<br />';
									}
								echo '</td>';
							}
							if ($showwc) 
							{
								echo '<td>' . get_the_title($row['weightclass']) .'</td>';
							}
							if ($showgender) 
							{
								echo '<td>' . get_the_title($row['gender']) .'</td>';
							}
							if ($showalign) 
							{
								echo '<td>' . get_the_title($row['alignment']) .'</td>';
							}
							
							echo '</tr>';
						}
					?>
					
					</table>
					
					<?php
					

					
					//print_r($returnvals);
					
					/*echo get_post_meta(get_the_ID(), 'team', true ) . '<br />';		
					print_r( get_post_meta(get_the_ID(), 'federations', false ) );
					echo '<br />';
					echo get_post_meta(get_the_ID(), 'weightclasses', false ) . '<br />';
					echo get_post_meta(get_the_ID(), 'divisions', false ) . '<br />';
					echo get_post_meta(get_the_ID(), 'genders', false ) . '<br />';
					echo get_post_meta(get_the_ID(), 'alignments', false ) . '<br />';*/

					?>
				</div>
			</div>
		</article>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
