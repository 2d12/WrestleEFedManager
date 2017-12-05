<?php
 /*Template Name: Workers
 */
 
get_header(); ?>

<div id="primary" class="content-area content-area-no-sidebar">
    <div id="content" role="main"> 
    <?php
    $mypost = array( 'post_type' => 'workers', );
    $loop = new WP_Query( $mypost );
    ?>
    <?php while ( $loop->have_posts() ) : $loop->the_post();?>
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
							<dl>
								<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Alignment:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php the_terms( get_the_ID(), 'alignment', '', ', ', ' ' ); ?></dt>
								<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Weight Class:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php the_terms( get_the_ID(), 'weightclass', '', ', ', ' ' ); ?></dt>
								<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Division:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php the_terms( get_the_ID(), 'division', '', ', ', ' ' ); ?></dt>
							</dl>
						</div>	
						
						<!-- Display vital statistics in right-aligned floating div -->
						<div style="float: right; width: 100%; margin:0 0 10px 10px; padding: 5px 0; border: 1px solid #000;  background: #e5e5e5;">
							<dl>
								<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Gender:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php the_terms( get_the_ID(), 'gender', '', ', ', ' ' ); ?></dt>
								<?php if (strlen(get_post_meta( get_the_ID(), 'height', true )) > 0) {
									?>
									<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Height:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php echo get_post_meta( get_the_ID(), 'height', true ); ?></dt>
								<?php }
								if (strlen(get_post_meta( get_the_ID(), 'weight', true )) > 0) {
									?>
									<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Weight:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php echo get_post_meta( get_the_ID(), 'weight', true ); ?></dt>
								<?php }
								if (strlen(get_post_meta( get_the_ID(), 'birth', true )) > 0) {
									?>
									<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Birthday:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php echo get_post_meta( get_the_ID(), 'birth', true ); ?></dt>
								<?php }
								 ?>
							</dl>
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
					the_content();
					?>
				</div>
			</div>
		</article>
 
    <?php endwhile; ?>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
