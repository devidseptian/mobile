<?php
/*  Loop template for the dt Slides 1.5.1 slideshow
	
	Copy "dt-slideshow.php" from "/dt-slides/" to your theme's directory to replace
	the plugin's default slideshow loop.
	
	Learn more about customizing the slideshow template for dt Slides: 
	http://www.jleuze.com/plugins/dt-slides/customizing-the-slideshow-template/
*/

	// Settings for slideshow loop

	global $post;
	
	$dt_posttemp = $post;
	$dt_options  = get_option( 'dtslides_options' );
	print_r($dt_options);
	$dt_nav      = $dt_options['slideshow_navigation'];
	$dt_count    = 1;
	$dt_loop     = new WP_Query( array(
	
		'post_type'      => 'dtslide',
		'slideshow'      => $slideshow,
		'posts_per_page' => $dt_options['slideshow_quantity']
		
	) ); ?>
	
	<?php // Check for slides
	
	if ( $dt_loop->have_posts() ) : ?>

		<section class="container">
		
			<div id="slidercontainer">
			
				<div id="slider">
				
					<?php // Loop which loads the slideshow
						
					while ( $dt_loop->have_posts() ) : $dt_loop->the_post(); ?>

						<div class="mslide mslide-<?php echo $dt_count; ?>">
							
							<?php // Adds slide image with Slide URL link
								
							if ( get_post_meta( $post->ID, "slide_url_value", $single = true ) != "" ): ?>
									
								<a href="<?php echo get_post_meta( $post->ID, "slide_url_value", $single = true ); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( 'featured-slide', array( 'title' => get_the_title() ) ); ?></a>
						
							<?php // Adds slide image without Slide URL link
								
							else: ?>
								
								<?php the_post_thumbnail( 'featured-slide', array( 'title' => get_the_title() ) ); ?>
								
							<?php endif; ?>
						
						</div><!-- .mslide -->
						
						<?php $dt_count++; ?>
						
					<?php endwhile; ?>
					
				</div>
				
			</div>
			
		</section>

		<?php // Reset the slideshow loop
		
		$post = $dt_posttemp;
		
		wp_reset_postdata(); ?>
		
	
	<?php endif; ?>