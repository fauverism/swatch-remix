<?php
/**
 * Template Name: Portfolio
 *
 * The portfolio page template displays your portfolio items with
 * a switcher to quickly filter between the various portfolio galleries. 
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options; 
 get_header();
?>
	 <div id="content" class="page col-full">
		<div id="main" class="col-left">
                                                                            
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?>  

            <div <?php post_class('drop-shadow lifted'); ?>>

			    <h1 class="title"><?php the_title(); ?></h1>
                
				<div class="entry">
		            <?php
		            	if ( have_posts() ) { the_post();
		            		the_content();
		            	}
		            ?>  
					<?php
						get_template_part( 'loop', 'portfolio' );
					?>
                </div>
			<div class="clear"></div>
            </div><!-- /.post -->
                                                            
		</div><!-- /#main -->
		
        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>