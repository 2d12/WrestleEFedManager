<?php
 /*Template Name: Workers
 */
 
get_header(); ?>

<div id="primary" class="content-area content-area-no-sidebar">
    <div id="content" role="main"> 
    <?php
    //$mypost = array( 'post_type' => 'workers', );
    //$loop = new WP_Query( $mypost );
    ?>
    <?php //while ( $loop->have_posts() ) : $loop->the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( ! is_single() ) { ?><a href="<?php echo esc_url( get_permalink() ); ?>"><?php } ?>

			<div class="single-entry">
				<header class="entry-header">
				   <h1 class="entry-title single-entry-title" style="color:black;"><?php the_title(); ?></h1>
					<div>
					<?php
					
					if (strlen(get_post_meta( get_the_ID(), 'aka', true )) > 0) {
						echo 'aka ';
						echo get_post_meta( get_the_ID(), 'aka', true);
					}					
					?>				   
					</div>
				</header>
				
				<script>
				function toggleMatchHistory() {
					var x = document.getElementById("matchHistory");
					if (x.style.display === "none") {
						x.style.display = "block";
					} else {
						x.style.display = "none";
					}
				}
				
				function toggleTitleHistory() {
					var x = document.getElementById("titleHistory");
					if (x.style.display === "none") {
						x.style.display = "block";
					} else {
						x.style.display = "none";
					}
				}
				</script>
				
				
					<!-- Sidebar Data -->
					<div style="float: right; width: 27%; margin:0 0 10px 10px; padding: 5px 0;">
				
						<!-- Display class data in right-aligned floating div -->
												
						<div style="float: right; width: 100%; margin:0 0 10px 10px; padding: 5px 0; border: 1px solid #000;  background: #e5e5e5;">
							<div style="text-align:center;"><?php the_post_thumbnail(); ?></div>
							<table>
								<tr><td>Alignment:</td><td><?php echo get_the_title(get_post_meta( get_the_ID(), 'alignment', true)); ?></td></tr>
								<tr><td>Weight Class:</td><td><?php echo get_the_title(get_post_meta( get_the_ID(), 'weightclass', true));?></td></tr>

								<tr><td>Gender:</td><td><?php echo get_the_title(get_post_meta( get_the_ID(), 'gender', true )); ?></td></tr>
								<?php if (strlen(get_post_meta( get_the_ID(), 'height', true )) > 0) {
									?>
									<tr><td>Height:</td><td><?php echo get_post_meta( get_the_ID(), 'height', true ); ?></td></tr>
								<?php }
								if (strlen(get_post_meta( get_the_ID(), 'weight', true )) > 0) {
									?>
									<tr><td>Weight:</td><td><?php echo get_post_meta( get_the_ID(), 'weight', true ); ?></td></tr>
								<?php }
								if (strlen(get_post_meta( get_the_ID(), 'birth', true )) > 0) {
									?>
									<tr><td>Birthday:</td><td><?php echo get_post_meta( get_the_ID(), 'birth', true ); ?></td></tr>
								<?php }
								 ?>
							</table>
						</div>
						
											
					</div>
				
				<!-- Display contents -->
				<div class="entry-content">

					<?php
					$link = $tname = $artist = false;
					if (strlen(get_post_meta( get_the_ID(), 'themelink', true )) > 0)
					{$link = true;}
					if (strlen(get_post_meta( get_the_ID(), 'themename', true )) > 0)
					{$tname = true;}
					if (strlen(get_post_meta( get_the_ID(), 'themeartist', true )) > 0)
					{$artist = true;}
					
					if ($link || $tname || $artist)
					{	
						if ($link && !($tname || $artist))
						{
							echo "<a href=\"";
							echo get_post_meta( get_the_ID(), 'themelink', true );
							echo "\">";
							echo "<b>Theme Song</b>";
							echo "<br />";
						}
						else
						{
							echo "<b>Theme Song:</b> ";

							if ($link == true)
							{
								echo "<a href=\"";
								echo get_post_meta( get_the_ID(), 'themelink', true );
								echo "\">";
							}
							if ($tname == true)
							{
								echo get_post_meta( get_the_ID(), 'themename', true );
							}
							
							if ($artist == true)
							{
								if ($tname == true)
								{
									echo " - ";
								}
								else
								{
									echo "By ";
								}
								echo get_post_meta( get_the_ID(), 'themeartist', true );
							}
							
							if ($link == true)
							{
								echo "</a>";
							}
							echo "<br />";
						}
					}
					
					if (strlen(get_post_meta( get_the_ID(), 'signatures', true )) > 0)
					{
						echo "<b>Signature Moves: </b>";
						echo get_post_meta( get_the_ID(), 'signatures', true );
						echo "<br />";
					}	
					echo "<br />";
					wp_reset_postdata();
					the_content();
					echo "<br />";
					
					if (strlen(get_post_meta( get_the_ID(), 'associates', true )) > 0)
					{
						echo "<b>Associates: </b>";
						echo get_post_meta( get_the_ID(), 'associates', true );
						echo "<br />";
					}

					$matchHistory = efed_worker_match_history(get_the_ID());
					if (count($matchHistory) > 0)
					{						
					?>
					<p />
					<div id="clickToShowMatchHistory" style="margin:0 0 10px 10px;"><button onclick="toggleMatchHistory()">Match History</button></div>
					<div id="matchHistory" style="width: 70%; margin:0 0 10px 10px; padding: 5px 0;display:none; background: #e5e5e5;">
					<table>
					<tr><th>Date</th><th>Match</th><th>Result</th></tr>
					<?php
					foreach ($matchHistory as $matchID => $matchResult)
					{
						echo '<tr><td>' . get_the_date( 'Y-m-d', $matchID ) . '</td><td>';

						echo '<a href="' . get_permalink($matchID) . '">'. get_the_title($matchID) . '</a></td><td>';
						echo $matchResult;
						//$victors = $matchResult->victors;
				
						echo '</td></tr>';
					}
					?>
					</table>					
					</div>

					<?php 
					}
										
					$titleHistory = efed_worker_title_history(get_the_ID()); 
					if (count($titleHistory) > 0)
					{
						//echo '<pre>';
						//print_r($titleHistory);
						//echo '</pre>'
					?>

					<div id="clickToShowTitleHistory" style="margin:0 0 10px 10px;"><button onclick="toggleTitleHistory()">Title History</button></div>
					<div id="titleHistory" style="width: 70%; margin:0 0 10px 10px; padding: 5px 0;display:none; background: #e5e5e5;">
					<table>
					<?php 

					foreach ($titleHistory as $titleID => $title)
					{
						echo '<tr><td  colspan="4" style="font-weight:bold;font-size:100%;">' . get_the_title($titleID) . ' (' . $title['count'] . 'x; ' . 
							$title['days'] . ' total days)</td></tr>';
						echo '<tr><td style="font-weight:bold;">Date Won</td><td style="font-weight:bold;">Previous Holder</td>';
						echo '<td style="font-weight:bold;">Next Champ</td><td style="font-weight:bold;">Date Lost</td></tr>';
						foreach ($title['reigns'] as $reign)
						{
								
							echo '<tr><td>' . $reign['win'] ;
							if (count($reign['cowinner']) > 0)
							{
								$cochampcount = 0;
								echo '<br />With ';
								foreach($reign['cowinner'] as $cowin)
								{
									$cochampcount++;
									if ($cochampcount > 1 && count($reign['cowinner']) > 2)
										echo ', ';
									else if ($cochampcount > 1)
										echo ' ';
									if ($cochampcount > 1 && $cochampcount == count($reign['cowinner']))
										echo 'and ';
									echo '<a href="';
									echo get_permalink($cowin);
									echo '">' . get_the_title($cowin) . '</a>';		
									//echo $champname;								
								}
							}							
							echo '</td>';
								
							echo '<td>';
							if (count($reign['prev']) > 0)
							{
								$champcount = 0;
								foreach($reign['prev'] as $champid)
								{
									$champcount++;
									if ($champcount > 1 && count($reign['prev']) > 2)
										echo ', ';
									else if ($champcount > 1)
										echo ' ';
									if ($champcount > 1 && $champcount == count($reign['prev']))
										echo 'and ';
									echo '<a href="';
									echo get_permalink($champid);
									echo '">' . get_the_title($champid) . '</a>';		
									//echo $champname;								
								}
							}
							else
							{
								echo "TITLE VACANT";
							}
							echo '</td>';
								
							if ($reign['lost'] == null)
							{
								echo '<td colspan="2">REIGNING CHAMPION</td></tr>';
							}
							else
							{
								echo '<td>';
								if (count($reign['next']) > 0)
								{
									$champcount = 0;
									foreach($reign['next'] as $champid)
									{
										$champcount++;
										if ($champcount > 1 && count($reign['next']) > 2)
											echo ', ';
										else if ($champcount > 1)
											echo ' ';
										if ($champcount > 1 && $champcount == count($reign['next']))
											echo 'and ';
										echo '<a href="';
										echo get_permalink($champid);
										echo '">' . get_the_title($champid) . '</a>';		
										//echo $champname;								
									}
								}
								else
								{
									echo "TITLE VACANT";
								}
								echo '</td>';
									
								echo '<td>' . $reign['lost'] . '</td></tr>';	
							}
						}
					}
					//echo '<pre>';
					//print_r($titleHistory);
					//echo '</pre>';
					
					?>
					</table>
					</div>
					<?php 					
					}	
					?>
					
				</div>
			</div>
		</article>
 
    <?php //endwhile; ?>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
