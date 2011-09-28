<?php
/*
Template Name: Full Width
*/
?>

<?php get_header(); ?>
       
    <div id="content" class="page col-full">
		<div id="main" class="fullwidth">
            
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?>  

            <?php if ( have_posts() ) { $count = 0; ?>
            <?php while ( have_posts() ) { the_post(); $count++; ?>
                                                                        
                <div <?php post_class('drop-shadow lifted'); ?>>

				    <h1 class="title"><?php the_title(); ?></h1>
                    
                    <div class="entry">
	                	<?php the_content(); ?>
						<div class="fix"></div>
	               	</div><!-- /.entry -->

					<?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<span class="small">', '</span>' ); ?>

                </div><!-- /.post -->
                                                    
			<?php
					} // End WHILE Loop
				} else {
			?>
				<div <?php post_class(); ?>>
                	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
                </div><!-- /.post -->
            <?php } ?>  
        
		</div><!-- /#main -->
		
    </div><!-- /#content -->
		
<?php get_footer(); ?>