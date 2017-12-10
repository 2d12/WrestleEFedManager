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
					
					<div id="clicktoshow"><button onclick="myFunction()">Results</button></div>
					<div id="results" style="display:none; background: #e5e5e5;">
					Results: 
					<?php
						echo get_post_meta( get_the_ID(), 'victors', true );
						echo " in ";
						echo get_post_meta( get_the_ID(), 'time', true );
						echo " with ";
						echo get_post_meta( get_the_ID(), 'finisher', true );
						echo "<br />";
						echo "Your referee for the match: ";
						echo get_post_meta( get_the_ID(), 'referee', true );
						echo "<br />";
						echo get_post_meta( get_the_ID(), 'titledefense', true );
					?>
					</div>
					
					<?php 
					wp_reset_postdata();
					echo "<br />";
					
					echo wpb_list_child_matches();
					?>
				</div>
			</div>
		</article>
 
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>
