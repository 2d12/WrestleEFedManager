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
					<tr><th>
					<?php
					$entrytype = get_post_meta(get_the_ID(), 'team', true);
					$showfed = get_post_meta(get_the_ID(), 'showfed', true);
					$showwc = get_post_meta(get_the_ID(), 'showwc', true);
					$showdiv = get_post_meta(get_the_ID(), 'showdiv', true);
					$showgender = get_post_meta(get_the_ID(), 'showgender', true);
					$showalign = get_post_meta(get_the_ID(), 'showalign', true);
					
					if ($entrytype = "individual")
					{
						echo 'Worker';
					}
					else if ($entrytype = "team")
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
					if ($showfed) {echo '<th>Federation</th>';} 
					if ($showwc) {echo '<th>Weight Class</th>';} 
					if ($showdiv) {echo '<th>Division</th>';} 
					if ($showgender) {echo '<th>Gender</th>';} 
					if ($showalign) {echo '<th>Alignment</th>';} 
					?>
					</tr>
					
					</table>
					
					<?php
					
					echo get_post_meta(get_the_ID(), 'team', true ) . '<br />';		
					print_r( get_post_meta(get_the_ID(), 'federations', false ) );
					echo '<br />';
					echo get_post_meta(get_the_ID(), 'weightclasses', false ) . '<br />';
					echo get_post_meta(get_the_ID(), 'divisions', false ) . '<br />';
					echo get_post_meta(get_the_ID(), 'genders', false ) . '<br />';
					echo get_post_meta(get_the_ID(), 'alignments', false ) . '<br />';

					?>
				</div>
			</div>
		</article>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
