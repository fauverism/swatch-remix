<?php
/**
 * Homepage "Page Content" Component.
 *
 * Display the content of a page, selected via the "Theme Options" screen.
 *
 * @since 1.0.0
 * @uses $woo_options array.
 */

global $woo_options;
 
if ( isset( $woo_options['woo_main_page'] ) && intval( $woo_options['woo_main_page'] ) != 0 ) {
?>
<div id="intro" class="widget widget_intro">
<?php query_posts( 'page_id=' . intval( $woo_options['woo_main_page'] ) ); ?>
<?php
	if ( have_posts() ) {
		while ( have_posts() ) { the_post();
?>

<h3 class="widget-title"><?php the_title(); ?></h3>				
<?php
			the_content();
		}
	}
	
	wp_reset_query();
?>
<div class="fix"></div><!--/.fix-->
</div><!--/#intro-->
<?php } // End IF Statement ?>