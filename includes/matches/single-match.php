<?php
 /*Template Name: Match
 */
 
get_header(); ?>

<div id="primary" class="content-area content-area-no-sidebar">
    <div id="content" role="main"> 
    <?php
    //$mypost = array( 'post_type' => 'match', );
   // $loop = new WP_Query( $mypost );
    ?>
    <?php //$loop->the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( ! is_single() ) { ?><a href="<?php echo esc_url( get_permalink() ); ?>"><?php } ?>

			<div class="single-entry">
				<header class="entry-header">
				   <h1 class="entry-title single-entry-title" style="color:black;"><?php the_title(); ?></h1>
				</header>
				<!-- Display contents -->
				<div class="entry-content"> 				
					<?php 
					wp_reset_postdata();
					the_content();
					//print_r(get_post_meta(get_the_ID()));
					?>
					<script>
					function myFunction() {
						var x = document.getElementById("results");
						if (x.style.display === "none") {
							x.style.display = "block";
						} else {
							x.style.display = "none";
						}
					}
					</script>
					
					<?php 
					if (count(get_post_meta( get_the_ID(), 'victors', true )) > 0 || get_post_meta( get_the_ID(), 'time', true ) != "" || 
						get_post_meta( get_the_ID(), 'finisher', true ) != "" || get_post_meta( get_the_ID(), 'referee', true ) != "" || 
						get_post_meta (get_the_ID(), 'title', true) != -1 )
						{
							
					?>
					
					<div id="clicktoshow"><button onclick="myFunction()">Results</button></div>
					<div id="results" style="display:none; background: #e5e5e5;">

					<?php

						$victor = get_post_meta( get_the_ID(), 'victors');
						$victorarray = array();
						foreach ($victor[0] as $victorID)
						{
							$victorarray[] = get_the_title($victorID);
						}

						$victorcount = 0;
						if (count($victorarray) > 0)
						{
							foreach($victorarray as $victorname)
							{
								$victorcount++;
								if ($victorcount > 1 && count($victorarray) > 2)
									echo ', ';
								else if ($victorcount > 1)
									echo ' ';
								if ($victorcount > 1 && $victorcount == count($victorarray))
									echo 'and ';
								echo $victorname;										
							}
						}
						
						if (get_post_meta( get_the_ID(), 'time', true ) != "")
						{
							echo " in ";
							echo get_post_meta( get_the_ID(), 'time', true );
						}
						if (get_post_meta( get_the_ID(), 'finisher', true ) != "")
						{
							echo " with ";
							echo get_post_meta( get_the_ID(), 'finisher', true );
						}
						if (get_post_meta( get_the_ID(), 'referee', true ) != "")
						{
							echo "<br />";
							echo "Your referee for the match: ";
							echo get_post_meta( get_the_ID(), 'referee', true );
						}
												
						if (get_post_meta (get_the_ID(), 'title', true) > 0 && get_post_meta(get_the_ID(), 'titleupdate', true) != "")
						{
							echo "<br />";
							$res = get_post_meta(get_the_ID(), 'titleupdate', true);
							
							echo get_the_title(get_post_meta (get_the_ID(), 'title', true));
							
							if ($res == "vacate")
								echo ' VACATED';
							else if ($res == "defense")
								echo ' successfully defended';
							else 
								echo ' -- NEW CHAMPION!';
							
						}
					}
					?>
					</div>
					
					<div style="position: absolute;left:0;bottom: 8px;width:100%;">
						<div style="text-align:center;margin:auto;"> 
						<?php  
						if ( $post->post_parent ) 
						{ ?>
							<a href="<?php echo get_permalink( $post->post_parent ); ?>" >
							<?php echo get_the_title( $post->post_parent ); ?>
							</a>
						<?php } ?>
						</div>
					</div>
					<div style="position: absolute;bottom: 8px;left: 8px"> <?php echo efed_previous_match(); ?></div>
					<div style="position: absolute;bottom: 8px;right: 8px"> <?php echo efed_next_match(); ?></div>
					
					<?php 
					echo "<br />";
					
					echo efed_list_child_matches();
					?>
				</div>
			</div>
		</article>
 
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
