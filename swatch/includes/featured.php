<?php
	global $woo_options;
	
	/* The Variables. */
	$settings = array(
	 	'entries' => 5, 
	 	'title' => 'true', 
	 	'content' => 'true'
	);
	
	/* Make sure the dynamic variables override the default values. */
	foreach ( $settings as $k => $v ) {
		if ( isset( $woo_options['woo_slider_' . $k] ) && ( $woo_options['woo_slider_' . $k] != '' ) ) {
			$settings[$k] = $woo_options['woo_slider_' . $k];
		}
	}
	
	$container_css = '';
	$slides = array();
	$slide_class = 'slide-content';
	
	$image_args = array(
					'width' => 900, 
					'height' => 338, 
					'class' => 'slide-img', 
					'link' => 'img', 
					'return' => 'true'
					);
	
	/* The Query Arguments. */
	$query_args = array(
					'suppress_filters' => false, 
 					'post_type' => 'slide', 
 					'post_status' => 'publish', 
 					'posts_per_page' => $settings['entries'], 
 					'orderby' => 'menu_order', 
 					'order' => 'ASC'
 					);
	
	/* The Query. */
	$query = query_posts( $query_args );
	
	/* Setup The Slides. */
	if ( count( $query ) == 1 ) { $container_css = ' single-slide'; } // Add a specific CSS class if only 1 slide is active.
	
	if ( have_posts() ) { $count = 0;
 		while( have_posts() ) { the_post(); $count++;
 			$id = $post->ID;

	 		// if ( woo_image( 'return=true' ) != '' || ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $id ) ) || ( woo_embed( '' ) != '' ) ) {
				$slides[$id] = $post;
				
				$meta = get_post_custom( $id );
				
				// Test for video assigned to this post.
				$video = woo_embed( 'width=500' );
				if ( $video != '' ) {
					$slides[$id]->slide_video = $video;
					$slides[$id]->has_video = true;
				} else {
					$slides[$id]->has_video = false;
				}
				
				// Test for image.
				if ( $slides[$id]->has_video == false && ( ( isset( $meta['image'] ) && ( $meta['image'][0] != '' ) ) || ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $id ) ) ) ) {
					$slides[$id]->has_image = true;
				}
				
				// Test for "URL" custom field.
				if ( isset( $meta['url'] ) && $meta['url'][0] != '' ) {
					$slides[$id]->slide_url = $meta['url'][0];
					$slides[$id]->has_url = true;
				} else {
					$slides[$id]->has_url = false;
				}
	 		// }
 		}
 	}
	
	/* The Display. */
	if ( count( $slides ) > 0 ) {
?>
	<div id="featured-slider">
		<div id="slide-box">
			<div class="slides_container<?php echo $container_css; ?>">
<?php
	$count = 0;
	foreach ( $slides as $k => $post ) { $count++;
	setup_postdata( $post );
	
	$anchor_start = '<a href="' . $post->slide_url . '" title="' . esc_attr( get_the_title() ) . '">';
	$anchor_end = '</a>';

	if ( $post->has_video ) { $slide_class = 'video-content'; } else if ( $post->has_image ) { $slide_class = 'slide-content'; } else { $slide_class = 'no-media-content'; }
?>
<div id="slide-id-<?php echo $post->ID; ?>" class="slide slide-number-<?php echo $count; ?>">
	<?php
		if ( ( $post->has_image && ! $post->has_video ) && ( $settings['title'] != 'true' && $settings['content'] != 'true' ) ) {
			// Silence is golden. Not .entry DIV required.
		} else {
	?>
	<div class="entry <?php echo $slide_class; if ( $slide_class != 'no-media-content' ) { echo ' fl'; } ?>">
	<?php
		if ( $settings['title'] == 'true' && $post->has_image && ! $post->has_video ) {
		$url = '';
		$title = get_the_title();
		
		if ( $post->has_url ) {
			$url = esc_url( $post->slide_url );
			$title = $anchor_start . $title . $anchor_end;
		}
	?>
	<h2 class="title"><?php echo $title; ?></h2>
	<?php } ?>
	<?php
		if ( $post->has_image && ! $post->has_video ) {
			$display_content = $settings['content'];
		} else {
			$display_content = 'true';
		}
		
		if ( $display_content == 'true' ) {
			the_content();
		}
	?>
	</div><!--/.entry-->
<?php
	}
	
	// Check for a slide image.
	if ( $post->has_image ) {
?>
	<div class="slide-image fl">
<?php echo $anchor_start . woo_image( $image_args ) . $anchor_end; ?>
	</div><!--/.slide-image-->
<?php
	}
	
	// Check for a video and no image.
	if ( $post->has_video && ! $post->has_image ) {
		echo woo_embed( 'width=500' );
	}
?>
	<div class="fix"></div><!--/.fix-->
</div><!--/.slide-->
<?php
	}
?>
			</div><!--/.slides_container .col-full-->
		</div><!--/#slide-box-->
	</div><!--/#slides-->
<?php
	} // End IF Statement

/* Reset the query. */
wp_reset_query();
?>