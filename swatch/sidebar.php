<?php 
	// Don't display sidebar if full width
	global $woo_options;
	if ( isset( $woo_options['woo_layout'] ) && $woo_options['woo_layout'] != 'layout-full' ) {
?>	
<div id="sidebar" class="col-right">

	<?php if ( woo_active_sidebar( 'primary' ) ) { ?>
    <div class="primary">
		<?php woo_sidebar( 'primary' ); ?>		           
	</div>        
	<?php } ?>
	
</div><!-- /#sidebar -->
<?php } ?>