<?php
 /*Template Name: Championship
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
				
				<!-- Sidebar Data -->
					<div style="float: right; width: 33%; margin:0 0 10px 10px; padding: 5px 0;">
				
						<!-- Display class data in right-aligned floating div -->
												
						<div style="float: right; width: 100%; margin:0 0 10px 10px; padding: 5px 0; border: 1px solid #000;  background: #e5e5e5;">
							<div style="text-align:center;"><?php the_post_thumbnail(); ?></div>
							
							<div style="text-align:center;">Eligibility</div>
							<table>
								<tr><td>Type:</td><td>
									<?php 
										$type = get_post_meta( get_the_ID(), 'type', true); 
										if ($type == 'singles') echo 'Singles Title';
										else if ($type == 'tag') echo 'Tag Team Title';
										else if ($type == '6tag') echo '6-Man Tag Team Title';
										else if ($type == '8tag') echo '8-Man Tag Team Title';
									?>
								</td></tr>
								
								<?php
									$fed = get_post_meta( get_the_ID(), 'federations');
									$fedarray = array();
									foreach ($fed[0] as $fedID)
									{
										$fedarray[] = get_the_title($fedID);
									}
									
								?>
								<tr><td>Fed/Division:</td><td>
								<?php 
								if (count($fedarray) > 0)
								{
									foreach($fedarray as $fedname)
									{
										echo $fedname . '<br />';
									}
								}
								else
								{
										echo "Any";
								}
								?>
								</td></tr>
								
								<?php
									$wc = get_post_meta( get_the_ID(), 'weightclasses');
									$weightsarray = array();
									foreach ($wc[0] as $weightID)
									{
										$weightsarray[] = get_the_title($weightID);
									}
									
								?>
								<tr><td>Weight Classes:</td><td>
								<?php 
								if (count($weightsarray) > 0)
								{
									foreach($weightsarray as $weightname)
									{
										echo $weightname . '<br />';
									}
								}
								else
								{
										echo "Any";
								}
								?>
								</td></tr>
								
								<?php
									$gen = get_post_meta( get_the_ID(), 'genders');
									$genderarray = array();
									foreach ($gen[0] as $genID)
									{
										$genderarray[] = get_the_title($genID);
									}
									
								?>
								<tr><td>Genders:</td><td>
								<?php 
								if (count($genderarray) > 0)
								{
									foreach($genderarray as $gendername)
									{
										echo $gendername . '<br />';
									}
								}
								else
								{
										echo "Any";
								}
								?>
								</td></tr>							
							</table>
						</div>	
					</div>
				<script>
				function toggleTitleHistory() {
					var x = document.getElementById("titleHistory");
					if (x.style.display === "none") {
						x.style.display = "block";
					} else {
						x.style.display = "none";
					}
				}
				</script>
				<!-- Display contents -->
				<div class="entry-content">
					<?php 
					wp_reset_postdata();
					the_content(); 
					?>
					<div id="clickToShowTitleHistory"><button onclick="toggleTitleHistory()">Title History</button></div>
					<div id="titleHistory" style="float: left; width: 63%; margin:0 0 10px 10px; padding: 5px 0;display:none; background: #e5e5e5;">
					<?php
					$titleHistory = efed_title_history(get_the_ID());
					//echo '<pre>';
					//print_r ($titleHistory);
					//echo '</pre>';
					if (count($titleHistory) > 0)
					{						
					?>
					<table>
					<tr>
					<th>Champion</th>
					<th>Date Won</th>
					<th>Times Won</th>
					<th>Reign Length</th>
					<th>Number of Defenses</th>
					<th>Total Days as Champion</th>
					</tr>
					
					<?php
					foreach ($titleHistory as $reign)
					{
						echo '<tr>';
						echo '<td>';
						foreach ($reign['champion'] as $champ)
						{
							$champarray[$champ] = get_the_title($champ);
						}

						$champcount = 0;
						if (count($champarray) > 0)
						{
							foreach($champarray as $champid => $champname)
							{
								$champcount++;
								if ($champcount > 1 && count($champarray) > 2)
									echo ', ';
								else if ($champcount > 1)
									echo ' ';
								if ($champcount > 1 && $champcount == count($champarray))
									echo 'and ';
								echo '<a href="';
								echo get_permalink($champid);
								echo '">' . $champname . '</a>';		
								//echo $champname;								
							}
						}
						else
						{
							echo "TITLE VACANT";
						}
						unset($champarray);
						echo '</td>';
						echo '<td>' . $reign['win'] . '</td>';
						echo '<td>' . $reign['reignNum'] . '</td>';
						echo '<td>' . $reign['length'] . ' Day';
						if ($reign['length'] > 1) echo 's';
						if ($reign['loss'] == null) echo '+';
						echo '</td>';
						echo '<td>' . $reign['defenses'] . '</td>';
						if ($reign['total'] == "N/A")
							echo '<td>' . $reign['total'] . '</td>';
						else
						{
							echo '<td>' . $reign['total'] . ' Day';
							if ($reign['total'] > 1) echo 's';
							if ($reign['loss'] == null) echo '+';
							echo '</td>';
						}
						echo '</tr>';
					}
					
					?>
					</table>
					
					<?php
					echo '</div>';
					}
					else
					{
					echo 'No Championship History';	
					}
					?>
				</div>
			</div>
		</article>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
