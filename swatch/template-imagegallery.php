<?php
/*
Template Name: Image Gallery
*/
?>

<?php get_header(); ?>
       
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

                <?php query_posts( 'showposts=60' ); ?>
                <?php if ( have_posts() ) { while ( have_posts() ) { the_post(); ?>				
                    <?php $wp_query->is_home = false; ?>

                    <?php woo_image( 'single=true&class=thumbnail alignleft' ); ?>
                
                <?php
                		}
                	}
                ?>	
                </div>
	<div class="clear"></div>
            </div><!-- /.post -->
                                                            
		</div><!-- /#main -->
		
        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>