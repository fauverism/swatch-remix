<?php
/**
 * Homepage "Portfolio Snapshots" Component.
 *
 * Display a snapshot of the recent items from your portfolio.
 *
 * @since 1.0.0
 * @uses $woo_options array.
 */

global $woo_options;

$query_args = array(
				'post_type' => 'portfolio', 
				'posts_per_page' => 3, 
				'orderby' => 'menu_order',
				'order' => 'asc'
				);
				
$title = apply_filters( 'woo_home_portfolio_title', __( 'Portfolio Snapshots', 'woothemes' ) );
?>
<div id="portfolio" class="widget widget-portfolio-snapshot">

<?php if ( $title != '' ) { ?><h3 class="widget-title"><?php echo $title; ?></h3><?php } ?>
<?php
	query_posts( $query_args );
	
	if ( have_posts() ) {
		while ( have_posts() ) { the_post();
			echo '<div id="portfolio-item-id-' . get_the_ID() . '" class="' . join( ' ', get_post_class( 'portfolio-item' ) ) . '">' . "\n";
			echo '<div class="portfolio-image drop-shadow lifted">' . woo_image( 'return=true&key=portfolio-image&width=240&height=150' ) . '</div><!--/.portfolio-image-->' . "\n";
			the_title( '<h3><a href="' . get_permalink( get_the_ID() ) . '">', '</a></h3>' . "\n" );
			echo get_the_term_list( get_the_ID(), 'portfolio-gallery', '<span class="portfolio-galleries">', ', ', '</span><!--/.portfolio-galleries-->' . "\n" );
			echo '</div>' . "\n";
		}
	}
	
	wp_reset_query();
?>
</div><!--/#portfolio-snapshot .widget-portfolio-snapshot-->