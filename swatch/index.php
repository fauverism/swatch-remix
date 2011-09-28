<?php
	get_header();
	global $woo_options;
?>

	<?php if ( isset( $woo_options['woo_slider'] ) && $woo_options['woo_slider'] == 'true' && is_home() && ! is_paged() ) { get_template_part( 'includes/featured' ); } // Load the Featured Slider ?>
    <?php if ( isset( $woo_options['woo_mini_features'] ) && $woo_options['woo_mini_features'] == 'true' && is_home() && ! is_paged() ) { get_template_part( 'includes/mini-features' ); } // Load the Mini Features ?>     

    <div id="content" class="col-full">
		<div id="main" class="fullwidth">

		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?> 
		<?php
			$homepage_columns = array( 'left' => 'pagecontent', 'middle' => 'portfolio', 'right' => 'blog' );
			
			$count = 0;
			foreach ( $homepage_columns as $k => $v ) { $count++;
			
			$css_class = 'block';
			if ( $count == count( $homepage_columns ) ) { $css_class .= ' last'; }
		?>
			<div id="homepage-column-<?php echo $count; ?>" class="<?php echo $css_class; ?>">
		<?php
			if ( is_active_sidebar( 'homepage-' . $k ) ) {
				dynamic_sidebar( 'homepage-' . $k );
			} else {
				get_template_part( 'includes/home', $v );
			}
		?>
			</div><!--/.block-->
		<?php
			}
		?>

			<div class="clear"></div><!--/.clear-->
		</div><!-- /#main -->

    </div><!-- /#content -->		
<?php get_footer(); ?>