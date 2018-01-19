<?php
 /*Template Name: Alignment
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
				<?php				
				the_content();
				?>
			</div>
		</div>
	</article>
	</div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
