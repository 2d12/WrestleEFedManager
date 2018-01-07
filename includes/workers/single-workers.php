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

					<!-- Sidebar Data -->
					<div style="float: right; width: 27%; margin:0 0 10px 10px; padding: 5px 0;">
				
						<!-- Display class data in right-aligned floating div -->
												
						<div style="float: right; width: 100%; margin:0 0 10px 10px; padding: 5px 0; border: 1px solid #000;  background: #e5e5e5;">
							<div style="text-align:center;"><?php the_post_thumbnail(); ?></div>
							<table>
								<tr><td>Alignment:</td><td><?php echo get_the_title(get_post_meta( get_the_ID(), 'alignment', true)); ?></td></tr>
								<tr><td>Weight Class:</td><td><?php echo get_the_title(get_post_meta( get_the_ID(), 'weightclass', true));?></td></tr>
								
							</table>
						</div>	
						
						<!-- Display vital statistics in right-aligned floating div -->
						<div style="float: right; width: 100%; margin:0 0 10px 10px; padding: 5px 0; border: 1px solid #000;  background: #e5e5e5;">
							<table>
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
					
					if (strlen(get_post_meta( get_the_ID(), 'themelink', true )) > 0)
					{$link = true;}
					if (strlen(get_post_meta( get_the_ID(), 'themename', true )) > 0)
					{$name = true;}
					if (strlen(get_post_meta( get_the_ID(), 'themeartist', true )) > 0)
					{$artist = true;}
					
					if ($link || $name || $artist)
					{					
						if ($link && !($name || $artist))
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
							if ($name == true)
							{
								echo get_post_meta( get_the_ID(), 'themename', true );
							}
							
							if ($artist == true)
							{
								if ($name == true)
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
					?>
				</div>
			</div>
		</article>
 
    <?php //endwhile; ?>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
