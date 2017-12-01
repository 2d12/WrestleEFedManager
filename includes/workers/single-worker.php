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
				   <h1 class="entry-title single-entry-title">
					<?php the_title(); 					
					?>Putting Something Here
					</h1>
				</header>
	 
					<!-- Sidebar Data -->
					<div style="float: right; width: 27%; margin:0 0 10px 10px; padding: 5px 0;">
					<?php the_terms( $post->ID, 'weightclass', 'Weight Class: ', ', ', ' ' ); ?>
					</div>
				<!-- Display contents -->
				<div class="entry-content"><?php the_content(); ?>And something for content?</div>
			</div>
		</article>
 
    <?php endwhile; ?>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>