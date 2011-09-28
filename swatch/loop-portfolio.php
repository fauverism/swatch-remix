<?php
/**
 * Loop - Portfolio
 *
 * This is a custom loop file, containing the looping logic for use in the "portfolio" page template, 
 * as well as the "portfolio-gallery" taxonomy archive screens. The custom query is only run on the page
 * template, as we already have the data we need when on the taxonomy archive screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

global $woo_options;
global $more; $more = 0;

/* Setup parameters for this loop. */
/* Make sure we only run our custom query when using the page template, and not in a taxonomy. */

$thumb_width = 242;
$thumb_height = 160;

/* Make sure our thumbnail dimensions come through from the theme options. */
if ( isset( $woo_options['woo_portfolio_thumb_width'] ) && ( $woo_options['woo_portfolio_thumb_width'] != '' ) ) {
	$thumb_width = $woo_options['woo_portfolio_thumb_width'];
}

if ( isset( $woo_options['woo_portfolio_thumb_height'] ) && ( $woo_options['woo_portfolio_thumb_height'] != '' ) ) {
	$thumb_height = $woo_options['woo_portfolio_thumb_height'];
}

if ( ! is_tax() ) {

$galleries = array();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
$query_args = array(
				'post_type' => 'portfolio', 
				'paged' => $paged, 
				'posts_per_page' => -1, 
				'orderby' => 'menu_order', 
				'order' => 'asc'
			);

/* Setup portfolio galleries navigation. */
$galleries = get_terms( 'portfolio-gallery' );

/* Optionally exclude navigation items. */
if ( isset( $woo_options['woo_portfolio_excludenav'] ) && ( $woo_options['woo_portfolio_excludenav'] != '' ) ) {
	$to_exclude = explode( ',', $woo_options['woo_portfolio_excludenav'] );
	
	if ( is_array( $to_exclude ) ) {
		foreach ( $to_exclude as $k => $v ) {
			$to_exclude[$k] = str_replace( ' ', '', $v );
		}
		
		/* Remove the galleries to be excluded. */
		foreach ( $galleries as $k => $v ) {
			if ( in_array( $v->slug, $to_exclude ) ) {
				unset( $galleries[$k] );
			}
		}
	}
}

/* If we have galleries, make sure we only get items from those galleries. */
if ( count( $galleries ) > 0 ) {

$gallery_slugs = array();
foreach ( $galleries as $g ) { $gallery_slugs[] = $g->slug; }

$query_args['tax_query'] = array(
								array(
									'taxonomy' => 'portfolio-gallery',
									'field' => 'slug',
									'terms' => $gallery_slugs
								)
							);
}

/* The Query. */			   
query_posts( $query_args );

} // End IF Statement ( is_tax() )

/* The Loop. */	
if ( have_posts() ) { $count = 0;
?>
<div id="portfolio">
<?php
	/* Display the gallery navigation (from theme-functions.php). */
	if ( is_page() || is_post_type_archive() ) { woo_portfolio_navigation( $galleries ); }
?>
	<div class="portfolio-items">
<?php
	while ( have_posts() ) { the_post(); $count++;
	
	/* Get the settings for this portfolio item. */
	$settings = woo_portfolio_item_settings( $post->ID );
?>
		<div id="portfolio-item-id-<?php the_ID(); ?>" <?php post_class( $settings['css_classes'] . ' portfolio-item' ); ?>>
		<?php
			/* Setup image for display and for checks, to avoid doing multiple queries. */
			$image = woo_image( 'return=true&key=portfolio-image&width=' . $thumb_width . '&height=' . $thumb_height . '&link=img&alt=' . the_title_attribute( array( 'echo' => 0 ) ) . '' );
			
			if ( $image != '' ) {
		?>
			<div class="portfolio-image drop-shadow lifted">
			<a <?php echo $settings['rel']; ?> title="<?php echo $settings['caption']; ?>" href="<?php echo $settings['large']; ?>" class="thumb" style="height: <?php echo $thumb_height; ?>px;">
				<?php echo $image; ?>
            </a>
            </div><!--/.portfolio-item-->
			<h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
			
			<?php echo get_the_term_list( get_the_ID(), 'portfolio-gallery', '<span class="portfolio-galleries">', ', ', '</span><!--/.portfolio-galleries-->' . "\n" ); ?>
		<?php
				// Output image gallery for lightbox
            	if ( ! empty( $settings['gallery'] ) ) {
                	foreach ( array_slice( $settings['gallery'], 1 ) as $img => $attachment ) {
                		echo '<a ' . $settings['rel'] . ' title="' . $attachment['caption'] . '" href="' . $attachment['url'] . '" class="gallery-image"></a>' . "\n";	                    
                	}
                }
			} // End IF Statement
		?>
		</div><!--/.group .post .portfolio-img-->
<?php
	} // End WHILE Loop
?>
	<div class="fix"></div><!--/.fix-->
	</div><!--/.portfolio-items-->
</div><!--/#portfolio-->
<?php
} else {
?>
<div <?php post_class(); ?>>
	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
</div><!-- .post -->
<?php
} // End IF Statement

rewind_posts();

woo_pagenav();
?>