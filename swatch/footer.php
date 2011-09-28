<?php global $woo_options; ?>
	<?php
		$total = $woo_options['woo_footer_sidebars']; if ( ! isset( $total ) ) { $total = 4; }
		if ( ( woo_active_sidebar( 'footer-1' ) ||
			   woo_active_sidebar( 'footer-2' ) ||
			   woo_active_sidebar( 'footer-3' ) ||
			   woo_active_sidebar( 'footer-4' ) ) && $total > 0 ) {

  	?>

	<div id="footer-widgets">
		<div class="col-full col-<?php echo $total; ?>">

		<?php $i = 0; while ( $i < $total ) { $i++; ?>
			<?php if ( woo_active_sidebar( 'footer-' . $i ) ) { ?>

		<div class="block footer-widget-<?php echo $i; ?>">
        	<?php woo_sidebar( 'footer-' . $i ); ?>
		</div>

	        <?php } ?>
		<?php } ?>

		<div class="fix"></div>
		</div>
	</div><!-- /#footer-widgets  -->
    <?php } ?>

	<div id="footer">
		<div class="col-full">

		<div id="copyright" class="col-left">
		<?php if( isset( $woo_options['woo_footer_left'] ) && $woo_options['woo_footer_left'] == 'true' ) {

				echo stripslashes( $woo_options['woo_footer_left_text'] );

		} else { ?>
			<p><?php bloginfo(); ?> &copy; <?php echo date( 'Y' ); ?>. <?php _e( 'All Rights Reserved.', 'woothemes' ); ?></p>
		<?php } ?>
		</div>

		<div id="credit" class="col-right">
        <?php if( isset( $woo_options['woo_footer_right'] ) && $woo_options['woo_footer_right'] == 'true' ) {

        	echo stripslashes( $woo_options['woo_footer_right_text'] );

		} else { ?>
			<p><?php _e( 'Powered by', 'woothemes' ); ?> <a href="http://www.wordpress.org">WordPress</a>. <?php _e( 'Designed by', 'woothemes' ); ?> <a href="<?php echo ( !empty( $woo_options['woo_footer_aff_link'] ) ? esc_url( $woo_options['woo_footer_aff_link'] ) : 'http://www.woothemes.com' ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/woothemes.png" width="74" height="19" alt="Woo Themes" /></a></p>
		<?php } ?>
		</div></div>
	</div><!-- /#footer  -->
</div><!-- /#wrapper -->
<?php wp_footer(); ?>
<?php woo_foot(); ?>
</body>
</html>