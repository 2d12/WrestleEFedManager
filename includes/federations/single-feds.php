<?php
 /*Template Name: Feds
 */
 
get_header(); ?>

<div id="primary" class="content-area content-area-no-sidebar">
    <div id="content" role="main"> 
    <?php
    //$mypost = array( 'post_type' => 'feds', );
    //$loop = new WP_Query( $mypost );
    ?>
    <?php /*while ( $loop->have_posts() ) : $loop->the_post();*/?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( ! is_single() ) { ?><a href="<?php echo esc_url( get_permalink() ); ?>"><?php } ?>

			<div class="single-entry">
				<header class="entry-header">
				   <h1 class="entry-title single-entry-title">
					<?php the_title(); 
					if (strlen(get_post_meta( get_the_ID(), 'abbreviation', true )) > 0) {
						echo ' (';
						echo get_post_meta( get_the_ID(), 'abbreviation', true);
						echo ')';
					}					
					?>
					</h1>
				</header>
	 
					<!-- Sidebar Data -->
					<div style="float: right; width: 55%; margin:0 0 10px 10px; padding: 5px 0;">
					
						<?php 
							$testThumbnail = (get_the_post_thumbnail() == "");
							$testFounded = (strlen(get_post_meta( get_the_ID(), 'founded', true )) == 0);
							$testClosed = (strlen(get_post_meta( get_the_ID(), 'closed', true )) == 0);
							$testOwner = (strlen(get_post_meta( get_the_ID(), 'owner', true )) == 0);
							if ($testThumbnail && $testFounded && $testClosed && $testOwner)
							{ // Do Nothing -- Nothing to Display 
						
						    }
							else
							{
						?>
						<!-- Display logo and vital statistics in right-aligned floating div -->
						<div style="float: right; width: 100%; margin:0 0 10px 10px; padding: 5px 0; border: 1px solid #000;  background: #e5e5e5;">
						
							<div align="center"><?php the_post_thumbnail(); ?></div>
							<dl>
								<?php if (strlen(get_post_meta( get_the_ID(), 'founded', true )) > 0) {
									?>
									<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Founded:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php echo get_post_meta( get_the_ID(), 'founded', true ); ?></dt>
								<?php }
								if (strlen(get_post_meta( get_the_ID(), 'closed', true )) > 0) {
									?>
									<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Closed:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php echo get_post_meta( get_the_ID(), 'closed', true ); ?></dt>
								<?php }
								if (strlen(get_post_meta( get_the_ID(), 'owner', true )) > 0) {
									?>
									<dd style="float: left; width: 50%; padding: 0 5px; margin: 0">Owner:</dd><dt style="float: left; width: 50%; padding: 0 5px; margin: 0"><?php echo get_post_meta( get_the_ID(), 'owner', true ); ?></dt>
								<?php } ?>
							</dl>
							<!-- LOGO ->?php the_post_thumbnail( array( 100, 100 ) ); ? -->
						</div>
							<?php } // End of else ?>
						<!-- Diplay current champions, if any, in a right-aligned floating div -->
						<div style="float: right; width: 100%; margin:0 0 10px 10px; padding: 5px 0; border: 1px solid #000;  background: #e5e5e5;">
							<div align="center">Current Champions</div>
							<?php 
								$champs = efed_get_current_champions(get_the_ID());
								if (count($champs) > 0)
								{
									echo '<table>';
									foreach ($champs as $key=>$title)
									{
										echo '<tr><td>' . get_the_title($key) . '</td><td>' . get_the_title($title[0]) . '</td></tr>';
									}
									echo '</table>';
								}
							?>
						</div>
						
						<!-- Diplay recent shows, with links -->
						<!--div style="float: right; width: 100%; margin:0 0 10px 10px; padding: 5px 0; border: 1px solid #000;  background: #e5e5e5;">
							<div align="center">Latest Episodes</div>
							<a href="">WWE Raw</a><br />
							<a href="">WWE Smackdown Live</a><br />
							<a href="">WWE NXT</a><br />
							<a href="">TLC: Tables, Ladders, & Chairs</a><br />
						</div -->
				</div>
				<!-- Display contents -->
				<div class="entry-content"><?php the_content(); ?></div>
			</div>
		</article>
 
    <?php /*endwhile;*/ ?>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>