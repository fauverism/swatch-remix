<?php
/**
 * Single Portfolio Item Template
 *
 * This template is the default portfolio item template. It is used to display content when someone is viewing a
 * singular view of a portfolio item ('portfolio' post_type).
 * @link http://codex.wordpress.org/Post_Types#Post
 *
 * @package WooFramework
 * @subpackage Template
 */

get_header();
global $woo_options;

$post_settings = woo_portfolio_item_settings( $post->ID );
?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
		           
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?>                      
<?php
	if ( have_posts() ) { $count = 0;
		while ( have_posts() ) { the_post(); $count++;
			
			$css_class = 'image';
			
			/* If we have a video embed code. */
			if ( $post_settings['embed'] != '' ) {
				echo woo_embed( 'width=500' );
				$css_class = 'video-excerpt';
			}
			
			/* If we have a gallery and don't have a video embed code. */
			if ( ( $post_settings['enable_gallery'] == 'true' ) && ( $post_settings['embed'] == '' ) ) {
				locate_template( array( 'includes/gallery.php' ), true );
				$css_class = 'gallery';
			}
			
			/* If we don't have a gallery and don't have a video embed code. */
			if ( ( $post_settings['enable_gallery'] == 'false' ) && ( $post_settings['embed'] == '' ) ) {
				echo '<div id="post-gallery" class="portfolio-img">' . woo_image( 'return=true&key=portfolio-image&width=' . $post_settings['width'] . '&class=portfolio-img' ) . '</div><!--/#post-gallery .portfolio-img-->' . "\n";
			}
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
		<h2><?php the_title(); ?></h2>
		<?php woo_portfolio_meta(); ?>
    	<div class="entry">	
    	<?php the_content(); ?>
		<?php
			/* Portfolio item extras (testimonial, website button, etc). */
			woo_portfolio_item_extras( $post_settings );
		?>
   		</div><!--/.entry-->
   	</div><!--/#post-->
<?php	
		}
	}
?>
</div><!-- #main -->

        <?php get_sidebar(); ?>

    </div><!-- #content -->
		
<?php get_footer(); ?>